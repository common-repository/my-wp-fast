=== My WP Fast ===
Tags: cache, fast, fast, lazy load, minify, cdn
Requires at least: 3.4
Tested up to: 5.2
Requires PHP: 5.6
License: No License

Make your Wordpress website super fast by minimizing and merging your JS and CSS files. This plugin have CDN option, lazy load and much more.

== Description ==
My WP Fast is the plugin that will make your WordPress super fast. 
With this plugin is possible to:
Minimize JS files and Merge
Minimize CSS files and Merge
Minimize HTML to make your site load faster
Automatic lazy load all images on your website
Configure a CDN to make your website even faster
Remove Wordpress information that your website doesn\'t need

https://www.youtube.com/watch?v=in7Y2WN77gw

== Installation ==
1 - Upload and install the plugin.
2 - Go to the plugin configuration page.
3 - Activate the Minify CSS and JS and other options you want to use.
4 - Visit your website pages to see if everything is working ok.
5 - If not turn on Debug and visit again and try to ignore some JS and CSS files slowly until you understand which one caused the problem.

== Frequently Asked Questions ==
- Is the plugin 100% free?
All functionalities are available in the free version but limited to 250 visits per day.

- What I should do if my Website is not working well after activating the plugin?
First, activate debug mode in configurations. 
Then visit your page that has the problem again.  
You should be able to see a list of the CSS files and JS files that were merged.
Then right-click somewhere on the page and select \"Inspect\" then select the Tab Console.
Understand if there is some JS error. You should be able to see it RED.
If there is some Javascript error you should go to the list of merged JS files and in back-office ignore files one by one until you understand which one is causing the problem.

- Can I use this with any template?
Will not work 100% of the times but if you debug and ignore the files that create problems you can use it with any template.

- I activate the Force SSL option but I don't have certificate
Just edit your .htaccess file search and remove the code: 
    BEGIN my_wp_fast_force_ssl
    .... 
    FINISH my_wp_fast_force_ssl

- I activate the GZIP option now my website is White: 
Just edit your .htaccess file search and remove the code: 
    BEGIN my_wp_fast_gzip
    .... 
    FINISH my_wp_fast_gzip