Sugi
====

The Sugi package is intended to make use of SugiPHP components and some 
other third party libraries a lot easier. This is done by making a 
facade class (or classes) for each of the components and libraries. 
For each component there might be a slightly different approach for 
accomplish the task, but still they all share some common logic. Besides 
ease of use facade is used to "glue" components with each other. One 
example is use of Events in other class facades, instead of their own 
custom methods of events, hooks and callback mechanisms.
This sounds pretty good to be TRUE, right? Well, there are some cons 
in this approach along with pros.

Advantages
----------
- All components and libraries are used statically and as a singletons.
- No need to create anything explicitly. Just use it.
- Components are not automatically loaded in your project until their first use.
- All the configurations (initializations) of the objects are done via config 
files, all share same logic and can be found in a single place.
- When you need, say, a second database connection (singletons won't work), 
you can still use a friendly factory method with a config file you specify. 
Note however that there are some facades which has no factory methods.

Disadvantages
-------------
- Some of the more complex options (and thus not often used ones) might not 
be available from the facade.
- Some of those complex options can be accessed but with some added complexity.
- Some of rarely used functions might not be compatible with a Sugi at all.
Examples include, but not limited to, some [Monolog](https://github.com/Seldaek/monolog) 
handlers if you use non standard log levels in your projects.

Conclusion
----------
For most of the projects (at least not very complex ones) there are no need
to handle more that one database, caching is done with either APC, Memcached 
or in a file and there is only one mailing transport. This means that there 
is no need for a developer to spend too much time to make different configurations 
for all the libraries he or she uses in a project. There is no sense to create 
several different objects that depends on each other just for sending a single 
email. But most important one, is when (and only if) the project grows there will 
still be an option to connect to a second database or make another mailing 
transportation in a traditional (and more complex) way.
