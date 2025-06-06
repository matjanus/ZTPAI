INSERT INTO "user" (username, password, role_id, email) VALUES
('admin', '$2y$13$I.6qdL/XJHP5y4WBzGkPSOVzizlXfnbPbOuc4wzIPtBjIHerpRuxi', 1, 'admin@admin.pl'),
('user1', '$2y$13$I.6qdL/XJHP5y4WBzGkPSOVzizlXfnbPbOuc4wzIPtBjIHerpRuxi', 2, 'user1@example.com'),
('strong', '$2y$13$OmxC2uPYaa.r.M9APH2LWuEzXIp21RC/GfE.yS95pOkqJVNsKgm3.', 2, 'admin@admin.COM');

INSERT INTO quiz ( quiz_name, owner_id, access_id) VALUES
('Zwierzęta', 2, 3),
('Jedzenie', 2, 2),
('Czasowniki', 3, 1),
('Przymiotniki', 3, 3),
('Ciało człowieka', 2, 2),
('Szkoła', 3, 1);

INSERT INTO quiz_vocabulary (quiz_id, word, translation) VALUES
(1, 'kot', 'cat'),
(1, 'pies', 'dog'),
(1, 'ptak', 'bird'),
(1, 'koń', 'horse'),
(1, 'krowa', 'cow'),
(2, 'chleb', 'bread'),
(2, 'masło', 'butter'),
(2, 'ser', 'cheese'),
(3, 'jeść', 'eat'),
(3, 'pić', 'drink'),
(4, 'duży', 'big'),
(4, 'mały', 'small'),
(5, 'ręka', 'hand'),
(5, 'noga', 'leg'),
(6, 'nauczyciel', 'teacher'),
(6, 'uczeń', 'student');

INSERT INTO favourite_quiz (patron_id, quiz_id) VALUES
(1, 1),
(2, 1),
(2, 2),
(2, 3),
(2, 4),
(3, 4),
(3, 5);

