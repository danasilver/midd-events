## Midd Events

_Updated January 19, 2014_

### Testing

You don't have to push to the Middlebury server everytime you want to test code.  In fact, you can run a local PHP development server.  From the project root directory, run:

```sh
$ php -S localhost:8000
```

then visit `localhost:8000` in your web browser.

### Organization

Right now, I'm moving all the PHP files to the root of the project so we have kind of clean URLs (i.e. not /templates/event.php).  After we have a MVP we can add a router that serves files based on the URL.  This is how frameworks like Rails, Django, Flask, Sinatra, etc. work and use URLs without file extensions on the end.

### Setup

Welcome to the git repository for Midd Events.  Here are a few things to get set up if you haven't used git or Github before.

 - Make sure you have git installed.  If you're not sure, run `which git` from the command line.  If you get a result (the path to the git executable), it's installed already.  For instructions on installing for your system, visit [http://git-scm.com/book/en/Getting-Started-Installing-Git](http://git-scm.com/book/en/Getting-Started-Installing-Git).  The website is also a complete resource on git.

 - Get to the directory where you'll store the project, then clone (essentially copy) the repository with this command. (Don't enter the `$`.  That just means you're running this from the command line.)

```sh
$ git clone https://github.com/danasilver/midd-events.git
```

 - `cd midd-events` and get to work!

