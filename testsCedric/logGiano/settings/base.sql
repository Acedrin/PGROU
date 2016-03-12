CREATE TABLE userLog (
				userLog_id INT AUTO_INCREMENT NOT NULL,
				userLog_time DATETIME NOT NULL,
				userLog_ip VARCHAR(15) NOT NULL,
				userLog_user VARCHAR(50) NOT NULL,
				userLog_token VARCHAR(255) NOT NULL,
				PRIMARY KEY (userLog_id)
);

CREATE TABLE funcLog (
				funcLog_id INT AUTO_INCREMENT NOT NULL,
				funcLog_time DATETIME NOT NULL,
				funcLog_user VARCHAR(50) NOT NULL,
				funcLog_ip VARCHAR(15) NOT NULL,
				funcLog_token VARCHAR(255) NOT NULL,
				funcLog_func TEXT NOT NULL,
				PRIMARY KEY (funcLog_id)
);

CREATE TABLE errorLog (
				errorLog_id INT AUTO_INCREMENT NOT NULL,
				errorLog_time DATETIME NOT NULL,
				errorLog_user VARCHAR(50) NOT NULL,
				errorLog_ip VARCHAR(15) NOT NULL,
				errorLog_token VARCHAR(255) NOT NULL,
				errorLog_error TEXT NOT NULL,
				PRIMARY KEY (errorLog_id)
);