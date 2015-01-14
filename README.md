Magento exension to add Sign2Pay as a payment method.

# Developers!

This is the current recipe how to develop the extension. It's not pretty,
but with Magento things seldom are. I have found this workflow to work best
across space and time. YMMV and these instructions might not be up to
date. When in doubt, reach out to me <joost@sign2pay.com>.

## Magento droplet

We have a Magento droplet living at http://magento.sign2pay.com.

This is just a pre-packaged DigitalOcean droplet. At one time we
uploaded the existing extension into this droplet. If at any one time
you need to start from scratch, this is the way to go.

To gain access to the droplet, have someone add your key to the root user.
If it's powered down, power it up using the Digital Ocean website.

The password for the admin user is in our [Wiki](https://github.com/Sign2Pay/sign2pay/wiki/Passwords).

To order something you'll want to search for `Socks`.

## PHP code

I recommend you create a snapshot right before you start to work on the
code. There is no version control on the PHP files.

Go to /var/www/html/magento/app where you'll find the code.

Due to Magento's amazing design the code is present in a number of
different directories. Seriously, to find stuff I would just `grep` across
the directory to find what you need.

The beauty of this setup is that you will be able to see your changes
right away. The horror of this method is that you won't be able to revert
major changes. Did you create a snapshot? If you didn't, now you know why.
If you messed up restore one of the earlier snapshots we made.

## Packaging the changes

At one point your changes are done and you can release a new version of
the extension. Visit the Magento admin panel, specifically
the section System > Magento Connect > Package Extensions. Choose `Load
Local Package` and choose `Sign2Pay_Mobile_Payments`. Then go back to
Package Info and create a new version number and release notes.

When satisfied, click `Save Data and Create Package`. You will see an
error on the screen. This error can safely be ignored! AWESOME! Download
the package from the server like so:

```
scp root@128.199.41.5:/var/www/html/magento/var/connect/Sign2Pay_Mobile_Payments-0.5.0.tgz .
```

Then comes the fun part, which is adding the changes on top of this repo.

Type something like this, assuming you're in the root of this repo:

```
tar xvfz ../Sign2Pay_Mobile_Payments-0.5.0.tgz

git add .
git commit -m "<describe your changes here>"

git tag -a v0.5.0

git push origin --all
git push origin --tags
```

There is one final thing to do. Go to the Digital Ocean admin screen and
make a snapshot of the VPS. Naturally you also put the correct version
number in the snapshot name.

## Releasing the plugin

Add the new version to the `public` folder of Sign2Pay and update the symlink
pointing to the latest release.

Assuming you're in the Sign2Pay main site repo:

```
cp ../Sign2Pay_Mobile_Payments-0.5.0.tgz public/integrations/magento
ln -sf public/integrations/magento/Sign2Pay_Mobile_Payments-0.5.0.tgz public/integrations/magento/latest.tgz

git add .
git commit -m "Released new version of Magento Sign2Pay extension"

git push origin master
```

When that's done comes the time to let the world know.

Firstly create a new Post on the docs.sign2pay.com Wordpress site. Explain the
changes in the new version and what's been fixed. Please point to the direct
version .tgz file. If needed, update the documentation of the plugin too.

Then send a message to all merchants who use the plugin.

TODO i will setup an Intercom segment for this

Finally create a new Twitter post for the sign2pay tech account.
