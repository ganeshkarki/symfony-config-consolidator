<p align="center">
<a href="http://symfonyconfigconsolidator-env.cwdgz45uue.us-east-2.elasticbeanstalk.com">Demo</a>
</p>

## About Symfony Config Consolidator

Symfony config consolidator is web app to merge the  `javascripts` and `stylesheets` config's for view.yml (Symfony 1.4).
The `view.yml` configurations as explained in [View Configuration Settings](https://symfony.com/legacy/doc/gentle-introduction/1_4/en/07-Inside-the-View-Layer) are defined at different levels (project and application) and the final parameter values results from a cascade. The stles and scripts can be added or removed from several configs as well as controller/template. This web-app accepts similar multiple yaml inputs and outputs final list.

Example:
Sample Application `view.yml`
```
default:
  stylesheets: [main]
```
Sample Module `view.yml`
```
indexSuccess:
  stylesheets: [special]

all:
  stylesheets: [additional]
```
Resulting indexSuccess View
```
<link rel="stylesheet" type="text/css" media="screen" href="/css/main.css" />
<link rel="stylesheet" type="text/css" media="screen" href="/css/additional.css" />
<link rel="stylesheet" type="text/css" media="screen" href="/css/special.css" />
```

Sample Module view.yml That Removes a File Defined at the Application Level (note `-` before main)
```
indexSuccess:
  stylesheets: [-main, special]

all:
  stylesheets: [additional]
```
## Local Setup
####  Step 1: Vagrant Up
```
vagrant up
```
#### Step 2: Add host mapping in local (Mac OS)
```
sudo vi /etc/hosts

# add hostname in /etc/hosts
192.168.10.10   symfony-config.consolidator
```
#### Step 3: Access website from browser
https://symfony-config.consolidator/

## Built with
- [Laravel](https://laravel.com)
- [Symfony YAML](https://symfony.com/doc/current/components/yaml.html)
- [AWS Elastic Beanstalk](https://aws.amazon.com/elasticbeanstalk/)
