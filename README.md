<b>Good news:</b>  The Bureau of Labor Statistics (BLS) has an API!

<b>Bad news:</b>  It's not CORS enabled (??!!!), so you can't access it through a simple JQuery AJAX call.  

<b>Variance:</b>  There are a number of convenient examples for various server side languages; PHP, Ruby, Java, Python, R, and a few more.

----

<b>Problem:</b>  If you're creating a webmap and you want to gather all the county data for your state in one API call - you might not be able to.  

The BLS has a limit of 50 geographies per API call.  (Uhoh, Colorado has 64 counties).  A minor annoyance, but not a big deal, right?  Make two calls, and merge a couple of arrays together.

<b>Another Issue:</b> Were you hoping to retrieve all data for the counties going back to 1990?  If so, you now need to make 4 API calls.  (The BLS will only give you 20 years worth of data per call.  If you need data for Texas you're now up to 12 API calls: data from 1990 to present for 254 counties.)

We're now beyond the point of merging arrays.  Plus, you really don't want the API response data in it's native format.  Compact is not the word I'm looking for.  It's the opposite of compact.  It would be a bandwidth killer for even a reasonable volume of data.

<b><i>"None of those problems appy to me.  I only need this year's data, and I'm only interested in Delaware." (3 counties)</i></b>
Even so, calling the API on the fly in your application is not a very wise decision; you'll burn through your quota of 500 API calls per day in no time.

<b>A different solution is in order:</b>

1.  Call the API (multiple times if necessary)
2.  Manipulate the data into a more usable format, and merge the data together from your multiple API calls.
3.  Store the data somewhere safe. (Amazon S3 bucket)
4.  Update the data automatically. (via cron)

I've written these scripts to be able to accomplish these tasks.  You'll notice they epitomize how a Javascript coder might write in PHP.  Don't be shy about calling me out, I can take the heat.  (pull requests also accepted).

If you just want the current BLS data... here's the link:
https://s3-us-west-2.amazonaws.com/blsdata/01_bls.json

**Replace the '01' in the above link with the FIPS code of your state.  For example, the link for Colorado data would be:**
https://s3-us-west-2.amazonaws.com/blsdata/08_bls.json


<b>Problem:</b>  These state by state files are useless for me.  I need data for the entire US.

Argh.  Okay fine.  Yes.  So to be practical, the architecture of your application would necessarily need to change.  It's not really practical (for loading times) to put everything in one huge file.  I'll create the data by year for the entire US.  That way you can ajax in the files as needed if someone toggles the YEAR dropdown in your application.
