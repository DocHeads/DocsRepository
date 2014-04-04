DROP TRIGGER IF EXISTS `users_creation_timestamp`; 
CREATE TRIGGER `users_creation_timestamp` BEFORE INSERT ON `users`
 FOR EACH ROW SET NEW.createDate = NOW();

DROP TRIGGER IF EXISTS `courses_creation_timestamp`; 
CREATE TRIGGER `courses_creation_timestamp` BEFORE INSERT ON `courses`
 FOR EACH ROW SET NEW.createDate = NOW();

DROP TRIGGER IF EXISTS `departments_creation_timestamp`; 
CREATE TRIGGER `departments_creation_timestamp` BEFORE INSERT ON `departments`
 FOR EACH ROW SET NEW.createDate = NOW();