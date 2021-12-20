## TODO

Command

1. generate admin
2. generate client
3. endpoint that returns client
4. endpoint that generate launch params for user & course

// 'http://localhost:8000/GolfExample_TCAPI/index.html?endpoint=http://localhost:1000/trax/api/18b34bd2-fe12-4e3f-a1fb-d95204da10e0/xapi/std&auth=Basic Y2xpZW50OmNsaWVudA==&actor={"mbox":"mailto:brian.miller@scorm.com","name":"Brian J. Miller","objectType":"Agent"}&registration=8175776f-8717-457d-b122-285ced399a96'

<URL to AU>
?endpoint=<URL to LMS Listener>
&fetch=<Fetch URL for the Authorization Token>
&actor=<Actor>
&registration=<Registration ID>
&activityId=<AU activity ID>
Example:

http://www.example.com/LA1/Start.html
?endpoint=http://lrs.example.com/lrslistener/
&fetch=http://lms.example.com/tokenGen.htm?k=2390289x0
&actor={"objectType": "Agent","account":
{"homePage": "http://www.example.com","name": "1625378"}}
&registration=760e3480-ba55-4991-94b0-01820dbd23a2
&activityId=http://www.example.com/LA1/001/intro

"http://localhost:1000/trax/api/18b34bd2-fe12-4e3f-a1fb-d95204da10e0/xapi/std";

uuid jest z tabeli trax_accesses
