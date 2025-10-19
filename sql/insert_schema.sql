INSERT INTO `user` (`ID`, `Username`, `RegDate`, `PasswdHash`) VALUES
                                                                   (1, 'admin', '2025-06-03 18:38:24', '$2y$10$r3iqH2cOAKdgXi2ot5fVse29r4gTi7ewRd5Q5w25QFj/53hDXIOxO'),
                                                                   (2, 'Picnic', '2025-06-03 18:52:49', '$2y$10$JXUUSZ1zTK8OrbYG5B/It.W40sD9tmCNRhl2qKYlNge7VMnm0lvXu'),
                                                                   (3, 'Такишотут', '2025-06-03 19:31:58', '$2y$10$/5hGFQZPW/GfmKXS5.QP7edDYzA7PiR8c5o.c/Y00M6/rFF0m0Aru'),
                                                                   (4, 'Kat', '2025-06-03 19:40:56', '$2y$10$PwIYfS6g2blz3LIxOGkrte39ZX14nP9PdFt9f/jrWtiuE4w/6jpry');

INSERT INTO `publication` (`ID`, `Title`, `Content`, `AuthorID`, `CreatedAt`) VALUES
                                                                                  (3, 'Autobahn in Italien', 'Zahlen oder nicht zahlen. Das ist die Frage!', 2, '2025-06-03 18:54:38'),
                                                                                  (4, 'Мой первый пост', 'Для чего это приложение?🤔', 3, '2025-06-03 19:34:04'),
                                                                                  (5, 'Второй пост)', 'Что-то мне не понятно как я могу найти пользователей...Только в случае, если знаю точно его ник?', 3, '2025-06-03 19:37:16'),
                                                                                  (6, 'Post', 'Hello world)))', 4, '2025-06-03 19:41:31'),
                                                                                  (7, 'Servus', 'I would like to post foto...Will it be possible?', 4, '2025-06-03 19:53:52'),
                                                                                  (8, 'Initial Post#2', 'Second initial post', 1, '2025-06-03 21:59:24');

INSERT INTO `like` (`LikedByID`, `LikedPublicationID`) VALUES
                                                           (1, 3),
                                                           (1, 4),
                                                           (1, 5),
                                                           (1, 6),
                                                           (3, 3),
                                                           (3, 6),
                                                           (4, 4),
                                                           (4, 5);
