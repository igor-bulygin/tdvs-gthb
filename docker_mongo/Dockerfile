FROM docker.io/mongo:3.2

MAINTAINER <alexandernst> alexandernst@gmail.com

RUN mkdir -p /data/db2

RUN echo "dbpath=/data/db2" > /etc/mongodb.conf

RUN echo "storage:" > /etc/mongod.conf
RUN echo "  dbPath: /data/db2" >> /etc/mongod.conf

ADD create_ddbb.js /tmp/

RUN chown -R mongodb:mongodb /data/db2

RUN mongod -f /etc/mongod.conf --fork --logpath /var/log/mongodb.log \
	&& sleep 5 \
	&& mongo todevise /tmp/create_ddbb.js \
	&& rm -rf /tmp/mongodb-27017.sock 
	
RUN chown -R mongodb:mongodb /data/db2
	
VOLUME /data/db2

CMD ["mongod", "-f", "/etc/mongod.conf"]