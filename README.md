## jtcron
Wordpress real cron functionality plugin 

## Install
Copy folder jtcron to wp-content/plugins and activate plugin via admin interface.
New file jtcron will be added to your wordpress root folder.
New folder etc will be added to your wp-content folder

##Use
There's file crontab.yaml within etc folder. You can add your new cron tasks in this file. Your tasks should be added in one format like below:

```yaml
	jobs:
	  testjob:
		schedule: '* * * * *'
		instance: \JtCron\Job\Text
		method: execute
```
or with function
```yaml
	jobs:
	  testjob:
		schedule: '* * * * *'
		function: my_awesome_function
```
Also you should config server crontab for running file <b>jtcron</b> from your root folder for every minute. You can do it with code like below:

```php
* * * * * /usr/bin/php /var/www/html/wordpress/jtcron
```

Enjoy your cron tasks
