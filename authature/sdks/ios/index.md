---
layout: authature
title: iOS Integration
---
<ol class="breadcrumb">
  <li><a href="/">Home</a></li>
  <li><a href="/authature">Authature</a></li>
  <li><a href="/authature/sdks">SDKs</a></li>
  <li>iOS</li>
</ol>

# Authature iOS SDK

The Authature iOS SDK is designed to facilitate the integration Authature within your iOS apps.

##Getting started
A pod will be released soon.

## Setting-up the AuthatureClient

First create an instance of AuthatureClientSettings to hold your client details:

{% highlight objective-c %}
AuthatureClientSettings *clientSettings =
  [[AuthatureClientSettings alloc] initWithClientId:@"your-client-id"
                                        callbackUrl:@"your-servers-oauth-callback-url"];
{% endhighlight %}

With this settings object, you can instantiate an AuthatureClient.
AuthatureClient uses a UIWebView to load the OAuth2 pages.
The delegate gives you control over where this webview ends up in your view hiërarchy.
More info on the delegate below.

{% highlight objective-c %}
self.client = [[AuthatureClient alloc] initWithSettings:clientSettings
                                               delegate:authatureDelegate];
{% endhighlight %}

## The delegate

The AuthatureClient uses a UIWebView to go through Authature's OAuth2 flow.
When the AuthatureClient is instantiated, you need to pass it an object which implements the AuthatureDelegate protocol.
Your delegate will be used to either obtain a controller to present the UIWebView, or the UIWebView will be passed to the delegate to present and dismiss.

In the first scenarion, simply implement this and return your controller.

{% highlight objective-c %}
- (UIViewController *)controllerForAuthatureWebView;
{% endhighlight %}

If you implement the protocol on your controller, it can be as simple as:

{% highlight objective-c %}
- (UIViewController *)controllerForAuthatureWebView{
  return self;
}
{% endhighlight %}

For the second scenario, you can control how the webview is presented and dismissed.
Use this approach for instance if you want to animate the transition.

{% highlight objective-c %}
- (void)presentAuthatureWebView:(UIWebView *) webView
                     completion:(void (^ (void))completion;

- (void)dismissAuthatureWebView;
{% endhighlight %}

The Webview will be dismissed before the token is fetched through your callback url. You may want to present some sort of indicator to your user while this is happening.
AuthatureClient will call authatureWebViewGotDismissed right after the webview got dismissed.


For instance:
{% highlight objective-c %}
- (void)authatureWebViewGotDismissed{
    self.hud = [MBProgressHUD showHUDAddedTo:self.view animated:YES];
    [self.hud setLabelText:@"Fetching token"];
}
{% endhighlight %}

In the callbacks (see below) you can hide the hud again.

### Starting an Authature flow

Depending on your setup Authature supports different scopes.
You can start the flow for any of these scopes by calling the corresponding method.
The userParams object holds properties for email, firstName and lastName.
These values will be used as parameters in the Authature flow.
All these methods have 2 blocks as parameters, a successCallback and an errorCallback.

(note, since these blocks will be called in an async fashion, its is best to keep a strong reference to the client object)

Start a flow for the capture scope (to capture the signature)

{% highlight objective-c %}
[client startAuthatureFlowForSignatureCaptureWithUserParams:userParams
                                                    success:^(NSDictionary *accessToken) {
                                                            //ok
                                                    }
                                                  andFailure:^(NSString *code, NSString *description) {
                                                            //fail
                                                  }
];
{% endhighlight %}

Authenticate scope (to authenticate the user)

{% highlight objective-c %}
[client startAuthatureFlowForAuthenticationWithUserParams:userParams
                                                  success:^(NSDictionary *accessToken) {
                                                            //ok
                                                  }
                                                andFailure:^(NSString *code, NSString *description) {
                                                            //fail
                                                }
];
{% endhighlight %}

PreApprove scope (to preapprove payments)

{% highlight objective-c %}
[client startAuthatureFlowForPreapprovalWithUserParams:userParams
                                              success:^(NSDictionary *accessToken) {
                                                            //ok
                                              }
                                            andFailure:^(NSString *code, NSString *description) {
                                                            //fail
                                            }
];
{% endhighlight %}

If you want a combination of scopes you can call the more generic method:

{% highlight objective-c %}
[client startAuthatureFlowForScope:@"authenticate,preapprove"
                    withUserParams:userParams
                          success:^(NSDictionary *accessToken) {
                                  //ok
                          }
                       andFailure:^(NSString *code, NSString *description) {
                                  //fail
                       }
];
{% endhighlight %}

##Token storage
The AuthatureClient can be configured to automatically store a token per requested scope (disabled by default).
If you turn on this feature, the AuthatureClient will use this token to send user params into the Authature flow if a new token is requested. This way, your users don't have to re-enter their details (e-mail) when going through the flow.

Turn it on like this:

{% highlight objective-c %}
authatureClient.automaticTokenStorageEnabled = TRUE;
{% endhighlight %}

If you want to get the automatically stored token for a scope:

{% highlight objective-c %}
[authatureClient getStoredTokenForScope:AUTHATURE_SCOPE_PRE_APPROVAL];
{% endhighlight %}

You can destroy a stored token like this:

{% highlight objective-c %}
[authatureClient destroyStoredTokenForScope:AUTHATURE_SCOPE_PRE_APPROVAL];
{% endhighlight %}

##AuthatureAccessTokenStorage
You can also conveniently interact with the token storage class directly to add/remove your tokens.
Tokens are organized by clientId and a key per token.

TIP: A good candidate for the key is the token value of the access token.

Store a token like this:

{% highlight objective-c %}
[AuthatureAccessTokenStorage saveAccessToken:accessToken
                                 forClientId:clientId
                                     withKey:accessToken[@"token"];
{% endhighlight %}

Reading a token:

{% highlight objective-c %}
return [AuthatureAccessTokenStorage getAccessTokenForClientId:clientId
                                                       andKey:"123"];
{% endhighlight %}

Delete a token:

{% highlight objective-c %}
[AuthatureAccessTokenStorage destroyAccessTokenForClientId:clientId
                                                    andKey:accessToken[@"token"]];
{% endhighlight %}

##Token verification
You can use the AuthatureClient instance to verify if a token is (still) valid for a certain scope.
You can do so by calling:

{% highlight objective-c %}
[authatureClient verifyValidity:token
                      forScope:AUTHATURE_SCOPE_PRE_APPROVAL
                      callBack:^(BOOL valid, NSDictionary *dictionary) {}
                 errorCallBack:^(NSError *error) {}];
{% endhighlight %}

If you are using the automatic token storage, you can verify the stored token by calling:

{% highlight objective-c %}
[authatureClient verifyStoredTokenValidityforScope:AUTHATURE_SCOPE_PRE_APPROVAL
                                          callBack:^(BOOL tokenIsValid, NSDictionary *responseObject) {}
                                     errorCallBack:^(NSError *error) {}];
{% endhighlight %}

##UI Components
When Authature is combined with Sign2Pay, a token can be linked to a bank account.
For conversion reasons, you may want to display the logo's of supported banks in your app.
When you have a preapproved scope token, you may want to show the logo of the bank associated to that token.

UIImageView+Authature and UIButton+Authature add methods to UIImageView and UIButton to make this happen.

### Bank logos based on IP
On a  UIImage:
{% highlight objective-c %}
[imageView useAsAuthatureBankLogos];
{% endhighlight %}

On a UIButton:
{% highlight objective-c %}
[button useAuthatureBankLogos];
{% endhighlight %}

### Bank logos for a specific country code
On a UIImage
{% highlight objective-c %}
[imageView useAsAuthatureBankLogosForCountryCode:@"BE"];
{% endhighlight %}

On a UIButton:
{% highlight objective-c %}
[button useAuthatureBankLogosForCountryCode:@"BE"];
{% endhighlight %}

###The bank logo linked to a token:
On a UIView
{% highlight objective-c %}
[imageView useAsAuthatureBankLogosWithToken:tokenForCheckout];
{% endhighlight %}

On a UIButton

{% highlight objective-c %}
[button useAuthatureBankLogosWithToken:tokenForCheckout];
{% endhighlight %}
