# Site Title [Optimising 2019](https://www.optimising.com.au)

This project is a custom WordPress theme built by Optimising. This theme uses [Sage 8.5.4](https://github.com/roots/sage) as a starter theme. The [Sage] theme is based on HTML5 Boilerplate, Node.js, gulp, Bower, Bootstrap and Sass.

## Features

* [Theme wrapper](https://roots.io/sage/docs/theme-wrapper/)
* [Bootstrap](http://getbootstrap.com/)
* [gulp](http://gulpjs.com/) build script that compiles both Sass and Less, checks for JavaScript errors, optimizes images, and concatenates and minifies files
* [BrowserSync](http://www.browsersync.io/) for keeping multiple browsers and devices synchronized while testing, along with injecting updated CSS and JS into your browser while you're developing
* [Bower](http://bower.io/) for front-end package management
* [asset-builder](https://github.com/austinpray/asset-builder) for the JSON file based asset pipeline
* ARIA roles and microformats
* [Multilingual ready](https://roots.io/wpml/) and over 30 available [community translations](https://github.com/roots/sage-translations)

## Prerequisites

| Prerequisite    | How to check | How to install
| --------------- | ------------ | ------------- |
| PHP >= 5.4.x    | `php -v`     | [php.net](http://php.net/manual/en/install.php) |
| Node.js >= 4.5  | `node -v`    | [nodejs.org](http://nodejs.org/) |
| gulp >= 3.8.10  | `gulp -v`    | `npm install -g gulp` |
| Bower >= 1.3.12 | `bower -v`   | `npm install -g bower` |


# Setup you local development environment
## Valet
```shell
$ Enter Valet instructions here
$ Enter Valet instructions here
$ Enter Valet instructions here
```

## Setup local development project

```shell
$ 'mkdir /Websites/files/directory' - Create your new local development environment directory
$ 'cd /Website/files/directory' - Navigation to your local development directory
$ git init - initialize a new empty repo
```

## Cloning files from Bitbucket [Recommended]

Add your SSH key to your Bitbucket account. For help setting this up check out the Bitbucket article [article](https://confluence.atlassian.com/bitbucket/set-up-an-ssh-key-728138079.html#SetupanSSHkey-ssh2SetupSSHonmacOS/Linux).

1. From Bitbucket, choose Bitbucket settings from your avatar in the lower left. The Account settings page opens.
2. Click SSH keys. If you've already added keys, you'll see them on this page.
3. In your terminal window, copy the contents of your public key file. If you renamed the key, replace id_rsa.pub with the public key file name.
On macOS, the following command copies the output to the clipboard:
```shell
$  pbcopy < ~/.ssh/id_rsa.pub
```
4. Select and copy the key output in the clipboard. If you have problems with copy and paste, you can open the file directly with Notepad. Select the contents of the file (just avoid selecting the end-of-file characters).
5. From Bitbucket, click Add key.
6. Enter a Label for your new key, for example, Default public key.
7. Paste the copied public key into the SSH Key field. You may see an email address on the last line when you paste. It doesn't matter whether or not you include the email address in the Key.
8. Bitbucket sends you an email to confirm the addition of the key.

Then from your Terminal window, git clone the repo you want to edit into your local dev directory.

```shell
$ 'git clone git@bitbucket.org:optimisingau/my-repo-name.git .' - Clone files into this direcrory
```

## Cloning files from WP Engine

Add your SSH key to the 'Git push' tab within the WP Engine admin panel. For help setting this up check out the WP Engine Support Garage [article](https://wpengine.com/support/set-git-push-user-portal/).
1. Log into the User Portal and navigate to the Overview page for your environment, then click on the Git push link on the left.
2. Enter a username for the SSH key, we prefer first initial & last name, but anything along those lines is fine.

```shell
$  pbcopy < ~/.ssh/id_rsa.pub
```

3. Add developer
4. Copy the git urls from the right side of the Git push tab
5. Open Terminal and enter your local dev directory

```shell
$ 'cd /Website/files/directory' - Navigation to your local development directory
$ 'git remote add product git@git.wpengine.com:production/wpengine-install.git' - Add remote [production] url
$ 'git remote add product git@git.wpengine.com:staging/wpengine-install.git' - Add remote [staging] url
```
6. Click on the Backup Points link from the left menu.
7. Select the latest backup point and then click Download Zip.
8. Once you've downloaded and unzipped the files copy them in to your local dev directory.

## Import Database
```shell
$ Enter DB instructions here
$ Enter DB instructions here
$ Enter DB instructions here
```

# Theme installation
## Creating a new project

```shell
$ 'cd Websites/my-local-project.com.au/wp-content/themes' - Navigate to the themes directory
$ 'composer create-project roots/sage my-new-theme-name 8.5.4' - Create and download a new Sage Sage 8.5.48.5.4 project
$ cd /my-new-theme-name - Navigate to the theme directory
$ 'npm install' - Install NPM dependancies
$ 'bower install' - Install frontend dependancies (Bootstrap)
$ 'gulp' - Compile and optimize the files in your assets directory
$ 'gulp watch' - Compile assets when file changes are made
```

## Installing an existing project

```shell
$ 'cd Websites/my-local-project.com.au/wp-content/theme/my-existing-theme-name' - Navigate to the theme directory
$ 'npm install' - Install NPM dependancies
$ 'bower install' - Install frontend dependancies (Bootstrap)
$ 'gulp' - Compile and optimize the files in your assets directory
$ 'gulp watch' - Compile assets when file changes are made
```

### Available gulp commands

* `gulp` — Compile and optimize the files in your assets directory
* `gulp watch` — Compile assets when file changes are made
* `gulp --production` — Compile assets for production (no source maps).

### Using BrowserSync

To use BrowserSync during `gulp watch` you need to update `devUrl` at the bottom of `assets/manifest.json` to reflect your local development hostname.

For example, if your local development URL is `http://project-name.localhost` you would update the file to read:
```json
...
  "config": {
    "devUrl": "http://project-name.localhost"
  }
...
```
If your local development URL looks like `http://localhost:8888/project-name/` you would update the file to read:
```json
...
  "config": {
    "devUrl": "http://localhost:8888/project-name/"
  }
...
```
# Deployment
```
$ 'cd ../../../' - Navigation to the root directory
$ 'git add ...' - Stage the files for deployment 
$ 'git commit -m "Deployment message here"' - Commit the changes
$ 'git remote -v' - Check the remotes available
$ 'git push remote-name master' - Push changes back to the remote
```

```shell
$ Enter Deploybot instructions here
$ Enter Deploybot instructions here
$ Enter Deploybot instructions here
```