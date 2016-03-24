CREATE TABLE userLog (
				userLog_id INT AUTO_INCREMENT NOT NULL,
				userLog_time DATETIME NOT NULL,
				userLog_ip VARCHAR(15) NOT NULL,
				userLog_client VARCHAR(50) NOT NULL,
				userLog_modalite VARCHAR(20) NOT NULL,
				userLog_action VARCHAR(255) NOT NULL,
				PRIMARY KEY (userLog_id)
);

CREATE TABLE servLog (
				servLog_id INT AUTO_INCREMENT NOT NULL,
				servLog_time DATETIME NOT NULL,
				servLog_client VARCHAR(50) NOT NULL,
				servLog_ip VARCHAR(15) NOT NULL,
				servLog_modalite VARCHAR(20) NOT NULL,
				servLog_service VARCHAR(256) NOT NULL,
				servLog_action VARCHAR(256) NOT NULL,
				PRIMARY KEY (servLog_id)
);

CREATE TABLE errorLog (
				errorLog_id INT AUTO_INCREMENT NOT NULL,
				errorLog_time DATETIME NOT NULL,
				errorLog_client VARCHAR(50) NOT NULL,
				errorLog_ip VARCHAR(15) NOT NULL,
				errorLog_error TEXT NOT NULL,
				PRIMARY KEY (errorLog_id)
);