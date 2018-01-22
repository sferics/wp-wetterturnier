
.. csv-table:: [Autogenerated table scheme of table "wp_wetterturnier_rerunrequest] Wetterturnier rerun requests, tell the backend to re-compute a specific tournament date due to changed observations/forecasts (admin modifications)."
    :header: "Field", "Type", "Null", "Key", "Default", "Extra"

    "ID","int(10) unsigned","NO","PRI","None","auto_increment"
    "cityID","smallint(5) unsigned","NO","","None",""
    "tdate","smallint(5) unsigned","NO","","None",""
    "userID","bigint(20) unsigned","NO","","None",""
    "placed","timestamp","NO","","CURRENT_TIMESTAMP",""
    "done","timestamp","YES","","None",""



* **Unique-key** named *PRIMARY* on ``(ID)``

