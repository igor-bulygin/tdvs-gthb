Todevise 2.0
================================

### TIPS

If you're reading this then you're either curious (good) or you're looking for
an easy way to fix the mess you created (bad). Either way, here it goes a few
tips you'll probably appreciate.

#### i18n

To scan the project for new/missing translation messages, run

~~~
./yii message config/i18n.php
~~~

New/missing translation messages will be appended to the existing ones, so you
don't have to worry about losing anything.

#### Migrations

To create a new migration file run

~~~
./yii mongodb-migrate/create migration_file_name
~~~

To revert the last applied migration run

~~~
./yii mongodb-migrate/down
~~~