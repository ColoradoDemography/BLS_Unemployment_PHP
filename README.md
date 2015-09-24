Good news:  The Bureau of Labor Statistics (BLS) has an API!

Bad news:  It's not CORS enabled (??!!!), so you can't access it through a simple JQuery AJAX call.  

Variance:  However, there are a number of convenient examples for various server side languages; PHP, Ruby, Java, Python, R, and a few more.

----

Problem:  If you want to gather all the county data for your state in one API call - you might not be able to.  The BLS has a limit of 50 geographies per API call.

Not a big deal, right?  Just merge a couple of arrays together.

Another Issue: Did you want all the data going back to 1990?  If so, you now need to make 4 API calls.  (That's just for Colorado.  If you needed Texas data, you're now up to 12 API calls to get data from 1990 to present for 254 counties.)

We're now beyond the point of merging arrays.  Plus, you probably don't want the API call response data in it's native format.  Compact is not the word I'm looking for.  It's the opposite of compact.

Additionally, calling the API on the fly is not an option either; you'll burn through your quota of 500 API calls per day in no time.

A different solution is in order:

1.  Call the API (multiple times if necessary)
2.  Manipulate the data into a more usable format, and merge the data together from your multiple API calls.
3.  Store the data somewhere safe. (Amazon S3 bucket)
4.  Update the data automatically. (via cron)

I've written these scripts to be able to accomplish these tasks.  I've written them in PHP (badly).  Because I am not too familiar with PHP, I basically write it as if it were Javascript.  That's why my code looks rediculous.

If you just want the current BLS data... that will be an option.  It's almost ready.