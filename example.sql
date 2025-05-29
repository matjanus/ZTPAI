INSERT INTO "user" (username, password, role_id, email) VALUES
('Admin', '$2y$13$I.6qdL/XJHP5y4WBzGkPSOVzizlXfnbPbOuc4wzIPtBjIHerpRuxi', 1, 'admin@admin.pl'),
('User1', '$2y$13$I.6qdL/XJHP5y4WBzGkPSOVzizlXfnbPbOuc4wzIPtBjIHerpRuxi', 2, 'user1@example.com'),
('User2', '$2y$13$I.6qdL/XJHP5y4WBzGkPSOVzizlXfnbPbOuc4wzIPtBjIHerpRuxi', 2, 'user2@example.com');

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

INSERT INTO favourite_quiz (user_id, quiz_id) VALUES
(2, 1),
(2, 2),
(2, 3),
(2, 4),
(3, 4),
(3, 5);

