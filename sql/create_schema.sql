CREATE TABLE `like` (
                        `LikedByID` int(11) NOT NULL,
                        `LikedPublicationID` int(11) NOT NULL
);

CREATE TABLE `publication` (
                               `ID` int(11) NOT NULL,
                               `Title` varchar(255) NOT NULL,
                               `Content` text NOT NULL,
                               `AuthorID` int(11) NOT NULL,
                               `CreatedAt` datetime NOT NULL
);

CREATE TABLE `user` (
                        `ID` int(11) NOT NULL,
                        `Username` varchar(255) DEFAULT NULL,
                        `RegDate` datetime NOT NULL,
                        `PasswdHash` varchar(255) NOT NULL
);

ALTER TABLE `like`
    ADD PRIMARY KEY (`LikedByID`,`LikedPublicationID`),
  ADD KEY `like_ibfk_2` (`LikedPublicationID`);

ALTER TABLE `publication`
    ADD PRIMARY KEY (`ID`),
  ADD KEY `AuthorID` (`AuthorID`);

ALTER TABLE `user`
    ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Username` (`Username`);

ALTER TABLE `publication`
    MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;


ALTER TABLE `user`
    MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

ALTER TABLE `like`
    ADD CONSTRAINT `like_ibfk_1` FOREIGN KEY (`LikedByID`) REFERENCES `user` (`ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `like_ibfk_2` FOREIGN KEY (`LikedPublicationID`) REFERENCES `publication` (`ID`) ON DELETE CASCADE;

ALTER TABLE `publication`
    ADD CONSTRAINT `publication_ibfk_1` FOREIGN KEY (`AuthorID`) REFERENCES `user` (`ID`);
