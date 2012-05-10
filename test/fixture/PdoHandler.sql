CREATE TABLE "log_table" (
    "id"        INTEGER PRIMARY KEY  AUTOINCREMENT  NOT NULL ,
    "level"     INTEGER NOT NULL ,
    "msg"       TEXT NOT NULL  check(typeof("msg") = 'text') ,
    "time" DATETIME NOT NULL
);
