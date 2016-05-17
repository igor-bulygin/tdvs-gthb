db.createUser(
    {
      user: "utodevise",
      pwd: "3],+UyY}=2KpBA^V",
      roles: [
         { role: "dbOwner", db: "todevise" }
      ]
    },
    {
		w: "majority",
		wtimeout: 5000
	}
);
db.createCollection("test");