Hello Creativebug,

This was a fun project, and while there are many ways to divide up the logic, I hope this is clean and clear. 
I show some OOP,some division of business and presentation, comments, and single use methods designed for ease of future maintenance.

There are only two working files and a readme file in the directory, so it can be installed in a php server in a single directory 
and accessed by the index.php file.

Feel free to contact me at 510-541-6628, or oaklandmax@gmail.com

-Max Perez

----

Testing:
To test with different countries, append the following debug values to the url as follows

maxperez/?debug=uk
maxperez/?debug=ar
maxperez/?debug=us

These will override the derived IP address to show how we handle visitors from other countries, 
or in the case that the test maching is calling the page as localhost or doesnt have its ip properly set.

The redirected url will feature the derived country code as a subdomain by default:
https://gb.creativebug.com or https://www.creativebug.com for US and null country codes.

Add "redirect=true" to url to see the user actually get routed the (fictional) country specific server site.

----

Addition debugging:
To see all the underlying data returned, add 'showdata=true' as follows
http://mperez.local/~max/maxperez/?showdata=true

----
VPN and Proxys:
You can try to block non-anonymous proxys with the url option "block_proxys=true" as follows
http://mperez.local/~max/maxperez/?block_proxys=true

----
Example:

Mix and match them as you  as follows
http://mperez.local/~max/maxperez/?showdata=true&debug=uk&block_proxys=true

----

Parameters of this project:
Design a system that would:
1.     Based on the physical location of the user, redirect the user to the localized country site (e.g. users accessing https://www.creativebug.com/index.php from United Kingdom will go to https://uk.creativebug.com/index.php or https://www.creativebug.com/uk/index.php. U.S. users should access https://www.creativebug.com/)
2.     Optional: consequently, if a United Kingdom user wanted to access the Canadian site, block that.
3.     Optional: add VPN detection and block if a VPN is used.
4.     Provide testing to prove that your code works.