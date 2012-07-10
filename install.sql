DROP TABLE IF EXISTS wcf1_tag;
CREATE TABLE wcf1_tag (
	tagID INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	languageID INT(10) NOT NULL DEFAULT 0,
	name VARCHAR(255) NOT NULL,
	synonymFor INT(10),
	UNIQUE KEY (languageID, name)
);

DROP TABLE IF EXISTS wcf1_tag_to_object;
CREATE TABLE wcf1_tag_to_object (
	objectID INT(10) NOT NULL,
	tagID INT(10) NOT NULL,
	objectTypeID INT(10) NOT NULL,
	languageID INT(10) NOT NULL DEFAULT 0,
	UNIQUE KEY (objectTypeID, languageID, objectID, tagID),
	KEY (objectTypeID, languageID, tagID),
	KEY (tagID, objectTypeID)
);

ALTER TABLE wcf1_tag ADD FOREIGN KEY (synonymFor) REFERENCES wcf1_tag (tagID) ON DELETE CASCADE;
ALTER TABLE wcf1_tag_to_object ADD FOREIGN KEY (tagID) REFERENCES wcf1_tag (tagID) ON DELETE CASCADE;
ALTER TABLE wcf1_tag_to_object ADD FOREIGN KEY (objectTypeID) REFERENCES wcf1_object_type (objectTypeID) ON DELETE CASCADE;