
.. csv-table:: [Autogenerated table scheme of table "wp_wetterturnier_bets] Wetterturnier Wordpress Plugin database which takes up the bets/forecasts of the users and the corresponding parameter-wise points."
    :header: "Field", "Type", "Null", "Key", "Default", "Extra"

    "userID","bigint(20) unsigned","NO","PRI","None",""
    "cityID","smallint(5) unsigned","NO","PRI","None",""
    "paramID","smallint(5) unsigned","NO","PRI","None",""
    "tdate","smallint(5) unsigned","NO","PRI","None",""
    "betdate","smallint(5) unsigned","NO","PRI","None",""
    "value","smallint(6)","NO","","None",""
    "points","float","YES","","None",""
    "placed","timestamp","NO","","CURRENT_TIMESTAMP","on update CURRENT_TIMESTAMP"
    "placedby","bigint(20) unsigned","NO","","0",""



* Non-unique key named *wp_wetterturnier_bets_idx_cityID* on ``(cityID)``
* Non-unique key named *wp_wetterturnier_bets_idx_betdate* on ``(betdate)``
* **Unique-key** named *userID* on ``(userID, cityID, paramID, tdate, betdate)``
* Non-unique key named *wp_wetterturnier_bets_idx_tournamentdate* on ``(tdate)``
Partitions on:


* ``PARTITION part2000 VALUES LESS THAN (10957) ENGINE = InnoDB``

* ``PARTITION part2001 VALUES LESS THAN (11323) ENGINE = InnoDB``

* ``PARTITION part2002 VALUES LESS THAN (11688) ENGINE = InnoDB``

* ``PARTITION part2003 VALUES LESS THAN (12053) ENGINE = InnoDB``

* ``PARTITION part2004 VALUES LESS THAN (12418) ENGINE = InnoDB``

* ``PARTITION part2005 VALUES LESS THAN (12784) ENGINE = InnoDB``

* ``PARTITION part2006 VALUES LESS THAN (13149) ENGINE = InnoDB``

* ``PARTITION part2007 VALUES LESS THAN (13514) ENGINE = InnoDB``

* ``PARTITION part2008 VALUES LESS THAN (13879) ENGINE = InnoDB``

* ``PARTITION part2009 VALUES LESS THAN (14245) ENGINE = InnoDB``

* ``PARTITION part2010 VALUES LESS THAN (14610) ENGINE = InnoDB``

* ``PARTITION part2011 VALUES LESS THAN (14975) ENGINE = InnoDB``

* ``PARTITION part2012 VALUES LESS THAN (15340) ENGINE = InnoDB``

* ``PARTITION part2013 VALUES LESS THAN (15706) ENGINE = InnoDB``

* ``PARTITION part2014 VALUES LESS THAN (16071) ENGINE = InnoDB``

* ``PARTITION part2015 VALUES LESS THAN (16436) ENGINE = InnoDB``

* ``PARTITION part2016 VALUES LESS THAN (16801) ENGINE = InnoDB``

* ``PARTITION part2017 VALUES LESS THAN (17167) ENGINE = InnoDB``

* ``PARTITION part2018 VALUES LESS THAN (17532) ENGINE = InnoDB``

* ``PARTITION part2019 VALUES LESS THAN (17897) ENGINE = InnoDB``

* ``PARTITION part2020 VALUES LESS THAN (18262) ENGINE = InnoDB``

* ``PARTITION part2021 VALUES LESS THAN (18628) ENGINE = InnoDB``

* ``PARTITION part2022 VALUES LESS THAN (18993) ENGINE = InnoDB``

* ``PARTITION part2023 VALUES LESS THAN (19358) ENGINE = InnoDB``

* ``PARTITION part2024 VALUES LESS THAN (19723) ENGINE = InnoDB``

* ``PARTITION part2025 VALUES LESS THAN (20089) ENGINE = InnoDB``

* ``PARTITION part2026 VALUES LESS THAN (20454) ENGINE = InnoDB``

* ``PARTITION part2027 VALUES LESS THAN (20819) ENGINE = InnoDB``

* ``PARTITION part2028 VALUES LESS THAN (21184) ENGINE = InnoDB``

* ``PARTITION part2029 VALUES LESS THAN (21550) ENGINE = InnoDB``


