DROP TABLE IF EXISTS awecode;

CREATE TABLE awecode (
    id          VARCHAR(255),
    title       VARCHAR(255),
    vimeoId     INTEGER,
    declare     LONGVARCHAR,
    description LONGVARCHAR,

    PRIMARY KEY(id)
);

INSERT INTO awecode ( id, title, vimeoId, declare, description ) VALUES (
    'console-readline',
    '<code>Hoa\Console\Readline</code>',
    66407773,
    '[{"id":"readline","name":"Console\/Readline.php","keyframes":[{"start":21,"end":77,"diff":"@@ -0,0 +1,5 @@\n+<?php\n+\n+require ''\/usr\/local\/lib\/Hoa\/Core\/Core.php'';\n+\n+echo fgets(STDIN);"},{"start":77,"end":173,"diff":"@@ -2,4 +2,14 @@\n \n require ''\/usr\/local\/lib\/Hoa\/Core\/Core.php'';\n \n-echo fgets(STDIN);\n+from(''Hoa'')\n+-> import(''Console.Readline.~'');\n+\n+$read = new Hoa\\Console\\Readline();\n+\n+do {\n+\n+    $line = $read->readLine(''> '');\n+    echo $line, \"\\n\";\n+}\n+while(false !== $line && ''quit'' !== $line);"},{"start":173,"end":231,"diff":"@@ -3,9 +3,18 @@\n require ''\/usr\/local\/lib\/Hoa\/Core\/Core.php'';\n \n from(''Hoa'')\n+-> import(''Console.Readline.Autocompleter.Word'')\n -> import(''Console.Readline.~'');\n \n $read = new Hoa\\Console\\Readline();\n+$read->setAutocompleter(new Hoa\\Console\\Readline\\Autocompleter\\Word(\n+    array(\n+        ''apple'',\n+        ''banana'', ''blackberry'', ''blueberry'',\n+        ''raspberry'',\n+        ''strawberry''\n+    )\n+));\n \n do {"},{"start":231,"end":343,"diff":"@@ -8,12 +8,7 @@ from(''Hoa'')\n \n $read = new Hoa\\Console\\Readline();\n $read->setAutocompleter(new Hoa\\Console\\Readline\\Autocompleter\\Word(\n-    array(\n-        ''apple'',\n-        ''banana'', ''blackberry'', ''blueberry'',\n-        ''raspberry'',\n-        ''strawberry''\n-    )\n+    get_defined_functions()[''internal'']\n ));\n \n do {"}]}]',
    '<p>Au sommaire : pourquoi et comment utiliser <code>Hoa\Console\Readline</code> ? Des exemples simples nous apprendrons à utiliser les raccourcis par défaut et nous aborderons même l''auto-complétion.</p>'
);
