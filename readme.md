<p align="center">
<a href="http://symfonyconfigconsolidator-env.cwdgz45uue.us-east-2.elasticbeanstalk.com/">Demo</a>
</p>

## About Symfony Config Consolidator

Symfony config consolidator is web app to merge and make the list of config's especially `javascripts` and `stylesheets` for Legacy 1.4 Symfony.
The `view.yml` as explained in [View Configuration Settings](https://symfony.com/legacy/doc/gentle-introduction/1_4/en/07-Inside-the-View-Layer) follows hierarchical rules for inclusion where it first applies application `view.yml` then module level `view.yml` config for `all` then the specifc template entries in the same file and then in template helper modifiers.

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

This web-app takes the input as yaml for app level, module level (`all`), page level and template level yaml to make final results

## Built with
- [Laravel](https://laravel.com)
- [Symfony YAML](https://symfony.com/doc/current/components/yaml.html)
- [AWS Elastic Beanstalk](https://aws.amazon.com/elasticbeanstalk/)
