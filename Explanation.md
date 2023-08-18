**Problem to be Solved**

Website administrators need a tool to analyze the internal linkage structure of their web pages in order to improve their SEO rankings. This involves identifying
how various web pages are connected to the home page, which is crucial for optimizing search engine visibility.

**Technical Solution**

To address this challenge, I have developed a WordPress plugin that provides administrators with the ability to crawl the homepage, extract internal hyperlinks, 
and display the results. The plugin provides a backend admin settings page, used transient for storing crawl results, and sitemap.html which is access for all types
of users.

**Technical Decisions**

I have used [WP Transient](https://developer.wordpress.org/apis/transients/) to store crawl results. Transient will automatically expired after defined internal. 
As plugin require to store crawl result in temporary storage so Transient is the best solution.

I have utilized AJAX and JavaScript to provide a seamless user experience. On click of Generate button, It will crawl the page.

Generated a static sitemap.html file to showcase the crawl results in a sitemap-like format. This provides a user-friendly representation of the internal link 
structure for visitors.

**How the Code Works**

When the admin click on "Generate" button from the settings page, an AJAX request is sent to the server, initiating the crawl process. The plugin fetches the home 
page's content, extracts internal hyperlinks, and stores the results in the temporary storage.

Used Databable to display the stored crawl results.

The plugin generates a sitemap.html file containing the crawl results as an unordered list. sitemap.xml stored in **wp-content/wsc** directory.

Plugin also creates homepage.html in **wp-content/wsc** directory.

**Achieving the Desired Outcome**

My solution empowers administrators by providing a straightforward and efficient tool to analyze the internal linkage structure of their website.
The admin can trigger a crawl, view the results in real-time on the settings page, and gain insights into the internal links' organization. 
This helps administrators make informed decisions to enhance SEO rankings and user navigation.

By carefully considering the problem, making key technical decisions, and implementing a well-structured solution, my WordPress plugin provides a valuable tool
for website administrators seeking to enhance their SEO rankings and optimize their website's internal link structure.
