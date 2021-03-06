source: Support/1-Minute-Polling.md

We now have support for polling data at intervals to fit your needs.

> Please be aware of the following:

  - You must also change your cron entry for `poller-wrapper.py` for this to work (if you change from the default 300 seconds).
  - Your polling _MUST_ complete in the time you configure for the heartbeat step value. See `/pollers/tab=pollers/` in your WebUI for your current value.
  - This will only affect RRD files created from the moment you change your settings.
  - This change will affect all data storage mechanisms such as MySQL, RRD and InfluxDB. If you decrease the values then please be aware of the increase in space use for MySQL and InfluxDB.
  
To make the changes, please navigate to `/settings/sub=external/` within your WebUI. Select RRDTool Setup and then update the two values for step and heartbeat intervals:

  - Step is how often you want to insert data, so if you change to 1 minute polling then this should be 60.
  - Heartbeat is how long to wait for data before registering a null value, i.e 120 seconds.
