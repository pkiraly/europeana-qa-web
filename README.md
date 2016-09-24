# europeana-qa-web
Web interface for the Europeana branch of Metadata Quality Assurance Framework 

# Prerequisits

## europeana-qa-api REST API

This API is used by the record display. It is a Spring based Java application running on Tomcat.

To start:

```
cd ~/apache-tomcat-8.0.36
# edit the configuration file:
nano lib/europeana-qa.custom.properties
bin/startup
```

To stop:

```
cd ~/apache-tomcat-8.0.36
bin/shutdown
```

## Apache Solr

An Apache Solr index is needed for the working of the API. To manage it:

```
cd ~/solr-5.5.0
bin/solr status
bin/solr start
bin/solr stop
```

