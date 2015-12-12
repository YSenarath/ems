*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-
Git::Init
http://symfony.com/doc/current/cookbook/workflow/new_project_git.html

And After Cloning for the first time....run
	composer install

Note: 
	Press Enter if u feel stuck...:)
	
HELP:
http://stackoverflow.com/questions/12059397/is-there-any-way-to-install-composer-globally-on-windows
*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-
Git@12/12/2015::
    Make it work#
        Do the above ^.
        Remove the last column of table 'employee'
        Add 'employee' with employee_id = <employee_id>
        Add user account (user_account) password: admin
            <employee_id> 	admin 	d033e22ae348aeb5660fc2140aec35850c4da997 	0
    What#
        Login, Main Interface, and User Management completed.
        Logout(just clear ur browser's cache for now) and Employee management not implemented.
    How#
        You can log to page through..
            http://localhost/PATH_TO_PROJECT_FOLDER/web/app_dev.php
            Note** Project folder should be inside www.
    CHANGES#
        Removed extra ems bundle. Add if someone wants it :)
        All(At least most of) Security and login related things are inside security folder in each category.
            Controllers
            Entity
            Resources (Not Form :()
        base.html.twig contains the nevBar. so do->>> {% extends 'AppBundle::base.html.twig' %} ... and change what ever
            you want. But don't change base.html.twig itself.
*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-