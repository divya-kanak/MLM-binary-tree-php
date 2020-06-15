SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE IF NOT EXISTS `mlm_rep` (
  `recordID` int(8) NOT NULL AUTO_INCREMENT,
  `sponsorID` int(8) NOT NULL DEFAULT '0',
  `leg` int(1) NOT NULL DEFAULT '0',
  `repID` varchar(32) NOT NULL DEFAULT '',
  `name` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`recordID`),
  UNIQUE KEY `repID` (`repID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
