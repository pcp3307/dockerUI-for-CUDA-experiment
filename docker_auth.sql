CREATE DATABASE IF NOT EXISTS dockertest;

USE dockertest;

--
-- Table structure for table `customers_auth`
--
CREATE TABLE IF NOT EXISTS `users_container` (
  `cid` varchar(64) NOT NULL,
  `username` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `ip` varchar(30) NOT NULL,
  `types` varchar(10) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`cid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=187 ;

CREATE TABLE IF NOT EXISTS `docker_resource` (
  `ip` varchar(16) NOT NULL,
  `quantity` int(4) NOT NULL DEFAULT 0, 
  PRIMARY KEY (`ip`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=187 ;

