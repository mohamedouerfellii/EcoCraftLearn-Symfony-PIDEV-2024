<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240427174203 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE notification_new_course (idnotification INT AUTO_INCREMENT NOT NULL, tutor_notified INT DEFAULT NULL, course_notified INT DEFAULT NULL, title VARCHAR(255) NOT NULL, is_checked TINYINT(1) NOT NULL, date DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, INDEX IDX_1576F2B189843FC4 (tutor_notified), INDEX IDX_1576F2B17B860482 (course_notified), PRIMARY KEY(idnotification)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE notification_new_course ADD CONSTRAINT FK_1576F2B189843FC4 FOREIGN KEY (tutor_notified) REFERENCES users (iduser) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE notification_new_course ADD CONSTRAINT FK_1576F2B17B860482 FOREIGN KEY (course_notified) REFERENCES courses (idcourse) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commandes DROP FOREIGN KEY commandes_ibfk_1');
        $this->addSql('ALTER TABLE commandes DROP FOREIGN KEY commandes_ibfk_2');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY comments_ibfk_1');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY comments_ibfk_2');
        $this->addSql('ALTER TABLE events DROP FOREIGN KEY events_ibfk_1');
        $this->addSql('ALTER TABLE eventsevaluations DROP FOREIGN KEY eventsevaluations_ibfk_1');
        $this->addSql('ALTER TABLE eventsevaluations DROP FOREIGN KEY eventsevaluations_ibfk_2');
        $this->addSql('ALTER TABLE eventsparticipations DROP FOREIGN KEY eventParticipant');
        $this->addSql('ALTER TABLE eventsparticipations DROP FOREIGN KEY eventId');
        $this->addSql('ALTER TABLE posts DROP FOREIGN KEY posts_ibfk_1');
        $this->addSql('ALTER TABLE postslikes DROP FOREIGN KEY postslikes_ibfk_1');
        $this->addSql('ALTER TABLE postslikes DROP FOREIGN KEY postslikes_ibfk_2');
        $this->addSql('ALTER TABLE products DROP FOREIGN KEY products_ibfk_1');
        $this->addSql('ALTER TABLE productsevaluations DROP FOREIGN KEY productsevaluations_ibfk_1');
        $this->addSql('ALTER TABLE productsevaluations DROP FOREIGN KEY productsevaluations_ibfk_2');
        $this->addSql('ALTER TABLE reportedposts DROP FOREIGN KEY reportedposts_ibfk_1');
        $this->addSql('ALTER TABLE savedposts DROP FOREIGN KEY savedposts_ibfk_1');
        $this->addSql('ALTER TABLE savedposts DROP FOREIGN KEY savedposts_ibfk_2');
        $this->addSql('ALTER TABLE souscarts DROP FOREIGN KEY fk_Cart');
        $this->addSql('ALTER TABLE souscarts DROP FOREIGN KEY fk_product');
        $this->addSql('DROP TABLE commandes');
        $this->addSql('DROP TABLE comments');
        $this->addSql('DROP TABLE events');
        $this->addSql('DROP TABLE eventsevaluations');
        $this->addSql('DROP TABLE eventsparticipations');
        $this->addSql('DROP TABLE posts');
        $this->addSql('DROP TABLE postslikes');
        $this->addSql('DROP TABLE products');
        $this->addSql('DROP TABLE productsevaluations');
        $this->addSql('DROP TABLE reportedposts');
        $this->addSql('DROP TABLE savedposts');
        $this->addSql('DROP TABLE souscarts');
        $this->addSql('ALTER TABLE carts DROP FOREIGN KEY carts_ibfk_1');
        $this->addSql('ALTER TABLE carts CHANGE owner owner INT DEFAULT NULL, CHANGE isConfirmed isconfirmed TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE carts ADD CONSTRAINT FK_4E004AACCF60E67C FOREIGN KEY (owner) REFERENCES users (iduser) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE carts RENAME INDEX iduser TO IDX_4E004AACCF60E67C');
        $this->addSql('ALTER TABLE collects DROP FOREIGN KEY collectsPts');
        $this->addSql('ALTER TABLE collects DROP FOREIGN KEY collector');
        $this->addSql('ALTER TABLE collects CHANGE collector collector INT DEFAULT NULL, CHANGE collectsDate collectsdate DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE collectsPts collectsPts INT DEFAULT NULL');
        $this->addSql('ALTER TABLE collects ADD CONSTRAINT FK_A17AFF6CCEDBF54C FOREIGN KEY (collector) REFERENCES users (iduser) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE collects ADD CONSTRAINT FK_A17AFF6C739B3C63 FOREIGN KEY (collectsPts) REFERENCES collectspts (idcollectspts) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE collects RENAME INDEX iduser TO IDX_A17AFF6CCEDBF54C');
        $this->addSql('ALTER TABLE collects RENAME INDEX idcollectspts TO IDX_A17AFF6C739B3C63');
        $this->addSql('ALTER TABLE collectspts CHANGE capacity capacity VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE coursefeedbacks DROP FOREIGN KEY feedbackOwner');
        $this->addSql('ALTER TABLE coursefeedbacks DROP FOREIGN KEY courseRated');
        $this->addSql('ALTER TABLE coursefeedbacks CHANGE owner owner INT DEFAULT NULL, CHANGE course course INT DEFAULT NULL, CHANGE content content VARCHAR(65535) NOT NULL, CHANGE postedDate posteddate DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE coursefeedbacks ADD CONSTRAINT FK_F91817BF169E6FB9 FOREIGN KEY (course) REFERENCES courses (idcourse) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE coursefeedbacks ADD CONSTRAINT FK_F91817BFCF60E67C FOREIGN KEY (owner) REFERENCES users (iduser) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE coursefeedbacks RENAME INDEX idcourse TO IDX_F91817BF169E6FB9');
        $this->addSql('ALTER TABLE coursefeedbacks RENAME INDEX iduser TO IDX_F91817BFCF60E67C');
        $this->addSql('ALTER TABLE courseparticipations DROP FOREIGN KEY courseP');
        $this->addSql('ALTER TABLE courseparticipations DROP FOREIGN KEY participantCourse');
        $this->addSql('ALTER TABLE courseparticipations CHANGE participant participant INT DEFAULT NULL, CHANGE course course INT DEFAULT NULL, CHANGE participationDate participationdate DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE sectionDone sectiondone INT NOT NULL');
        $this->addSql('ALTER TABLE courseparticipations ADD CONSTRAINT FK_911BC21F169E6FB9 FOREIGN KEY (course) REFERENCES courses (idcourse) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE courseparticipations ADD CONSTRAINT FK_911BC21FD79F6B11 FOREIGN KEY (participant) REFERENCES users (iduser) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE courseparticipations RENAME INDEX course TO IDX_911BC21F169E6FB9');
        $this->addSql('ALTER TABLE courseparticipations RENAME INDEX iduser TO IDX_911BC21FD79F6B11');
        $this->addSql('ALTER TABLE courses DROP FOREIGN KEY tutorId');
        $this->addSql('ALTER TABLE courses CHANGE tutor tutor INT DEFAULT NULL, CHANGE description description VARCHAR(65535) NOT NULL, CHANGE postedDate posteddate DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE nbrRegistred nbrregistred INT NOT NULL, CHANGE rate rate DOUBLE PRECISION NOT NULL, CHANGE nbrPersonRated nbrpersonrated INT NOT NULL');
        $this->addSql('ALTER TABLE courses ADD CONSTRAINT FK_A9A55A4C99074648 FOREIGN KEY (tutor) REFERENCES users (iduser) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE courses RENAME INDEX tutor TO IDX_A9A55A4C99074648');
        $this->addSql('ALTER TABLE creditcards DROP FOREIGN KEY ownerCard');
        $this->addSql('ALTER TABLE creditcards CHANGE owner owner INT DEFAULT NULL');
        $this->addSql('ALTER TABLE creditcards ADD CONSTRAINT FK_5E2597DBCF60E67C FOREIGN KEY (owner) REFERENCES users (iduser) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE creditcards RENAME INDEX owner TO IDX_5E2597DBCF60E67C');
        $this->addSql('ALTER TABLE quizanswers DROP FOREIGN KEY idUser');
        $this->addSql('ALTER TABLE quizanswers DROP FOREIGN KEY idQuizz');
        $this->addSql('ALTER TABLE quizanswers CHANGE student student INT DEFAULT NULL, CHANGE quizz quizz INT DEFAULT NULL, CHANGE answerDate answerdate DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE quizanswers ADD CONSTRAINT FK_44A7D1DC7C77973D FOREIGN KEY (quizz) REFERENCES quizzes (idquiz) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE quizanswers ADD CONSTRAINT FK_44A7D1DCB723AF33 FOREIGN KEY (student) REFERENCES users (iduser) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE quizanswers RENAME INDEX idquizz TO IDX_44A7D1DC7C77973D');
        $this->addSql('ALTER TABLE quizanswers RENAME INDEX iduser TO IDX_44A7D1DCB723AF33');
        $this->addSql('ALTER TABLE quizquestions DROP FOREIGN KEY quiz');
        $this->addSql('ALTER TABLE quizquestions CHANGE quiz quiz INT DEFAULT NULL, CHANGE question question VARCHAR(65535) NOT NULL, CHANGE choice_1 choice_1 VARCHAR(65535) NOT NULL, CHANGE choice_2 choice_2 VARCHAR(65535) NOT NULL, CHANGE choice_3 choice_3 VARCHAR(65535) NOT NULL, CHANGE choice_4 choice_4 VARCHAR(65535) NOT NULL');
        $this->addSql('ALTER TABLE quizquestions ADD CONSTRAINT FK_72D8E8F0A412FA92 FOREIGN KEY (quiz) REFERENCES quizzes (idquiz) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE quizquestions RENAME INDEX quiz TO IDX_72D8E8F0A412FA92');
        $this->addSql('ALTER TABLE quizzes DROP FOREIGN KEY idSection');
        $this->addSql('ALTER TABLE quizzes CHANGE section section INT DEFAULT NULL');
        $this->addSql('ALTER TABLE quizzes ADD CONSTRAINT FK_94DC9FB52D737AEF FOREIGN KEY (section) REFERENCES sections (idsection) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE quizzes RENAME INDEX idsection TO IDX_94DC9FB52D737AEF');
        $this->addSql('ALTER TABLE sections DROP FOREIGN KEY idCourse');
        $this->addSql('ALTER TABLE sections CHANGE course course INT DEFAULT NULL, CHANGE description description VARCHAR(65535) NOT NULL, CHANGE postedDate posteddate DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE sections ADD CONSTRAINT FK_2B964398169E6FB9 FOREIGN KEY (course) REFERENCES courses (idcourse) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sections RENAME INDEX idcourse TO IDX_2B964398169E6FB9');
        $this->addSql('ALTER TABLE users CHANGE isActive isactive TINYINT(1) NOT NULL, CHANGE nbrPtsCollects nbrptscollects INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commandes (owner INT NOT NULL, cart INT NOT NULL, idCommande INT AUTO_INCREMENT NOT NULL, commandeDate DATE DEFAULT \'current_timestamp()\' NOT NULL, email VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, city VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, phone INT NOT NULL, status VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, latitude DOUBLE PRECISION NOT NULL, longitude DOUBLE PRECISION NOT NULL, total DOUBLE PRECISION NOT NULL, INDEX idUser (owner), INDEX idCart (cart), PRIMARY KEY(idCommande)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE comments (post INT NOT NULL, owner INT NOT NULL, idComment INT AUTO_INCREMENT NOT NULL, content TEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, attachment VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_general_ci`, INDEX idPost (post), INDEX idUser (owner), PRIMARY KEY(idComment)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE events (owner INT NOT NULL, idEvent INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, description TEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, startDate DATETIME NOT NULL, endDate DATETIME NOT NULL, attachment VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, eventType VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, place VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, placeNbr INT NOT NULL, price INT NOT NULL, INDEX idUser (owner), PRIMARY KEY(idEvent)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE eventsevaluations (event INT NOT NULL, evaluator INT NOT NULL, idEvaluation INT AUTO_INCREMENT NOT NULL, rate INT NOT NULL, INDEX idEvent (event), INDEX idUser (evaluator), PRIMARY KEY(idEvaluation)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE eventsparticipations (event INT NOT NULL, participant INT NOT NULL, idParticipation INT AUTO_INCREMENT NOT NULL, INDEX idEvent (event), INDEX idUser (participant), PRIMARY KEY(idParticipation)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE posts (owner INT NOT NULL, idPost INT AUTO_INCREMENT NOT NULL, content TEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, attachment VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_general_ci`, postedDate DATETIME DEFAULT \'current_timestamp()\' NOT NULL, INDEX idUser (owner), PRIMARY KEY(idPost)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE postslikes (post INT NOT NULL, idLike INT AUTO_INCREMENT NOT NULL, idUser INT NOT NULL, action INT DEFAULT 0 NOT NULL, INDEX idPost (post), INDEX idUser (idUser), PRIMARY KEY(idLike)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE products (owner INT NOT NULL, idProduct INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, description TEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, image VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, price DOUBLE PRECISION NOT NULL, addDate DATE DEFAULT \'current_timestamp()\' NOT NULL, quantite INT DEFAULT NULL, INDEX idUser (owner), PRIMARY KEY(idProduct)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE productsevaluations (product INT NOT NULL, evaluator INT NOT NULL, idEvaluation INT AUTO_INCREMENT NOT NULL, rate DOUBLE PRECISION NOT NULL, evaluationDate DATE DEFAULT \'current_timestamp()\' NOT NULL, INDEX idProduct (product), INDEX idUser (evaluator), PRIMARY KEY(idEvaluation)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE reportedposts (idReport INT AUTO_INCREMENT NOT NULL, idPost INT NOT NULL, type VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, reason VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, nbrReports INT DEFAULT 1 NOT NULL, INDEX idPost (idPost), PRIMARY KEY(idReport)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE savedposts (owner INT NOT NULL, post INT NOT NULL, idSavedPost INT AUTO_INCREMENT NOT NULL, INDEX owner (owner), INDEX post (post), PRIMARY KEY(idSavedPost)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE souscarts (id_product INT NOT NULL, id_SousCarts INT AUTO_INCREMENT NOT NULL, idCart INT NOT NULL, QuantiteProduct INT NOT NULL, INDEX fk_Cart (idCart), INDEX fk_product (id_product), PRIMARY KEY(id_SousCarts)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE commandes ADD CONSTRAINT commandes_ibfk_1 FOREIGN KEY (owner) REFERENCES users (idUser) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commandes ADD CONSTRAINT commandes_ibfk_2 FOREIGN KEY (cart) REFERENCES carts (idCarts) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT comments_ibfk_1 FOREIGN KEY (owner) REFERENCES users (idUser) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT comments_ibfk_2 FOREIGN KEY (post) REFERENCES posts (idPost) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE events ADD CONSTRAINT events_ibfk_1 FOREIGN KEY (owner) REFERENCES users (idUser) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE eventsevaluations ADD CONSTRAINT eventsevaluations_ibfk_1 FOREIGN KEY (evaluator) REFERENCES users (idUser) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE eventsevaluations ADD CONSTRAINT eventsevaluations_ibfk_2 FOREIGN KEY (event) REFERENCES events (idEvent) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE eventsparticipations ADD CONSTRAINT eventParticipant FOREIGN KEY (participant) REFERENCES users (idUser) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE eventsparticipations ADD CONSTRAINT eventId FOREIGN KEY (event) REFERENCES events (idEvent) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE posts ADD CONSTRAINT posts_ibfk_1 FOREIGN KEY (owner) REFERENCES users (idUser) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE postslikes ADD CONSTRAINT postslikes_ibfk_1 FOREIGN KEY (idUser) REFERENCES users (idUser) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE postslikes ADD CONSTRAINT postslikes_ibfk_2 FOREIGN KEY (post) REFERENCES posts (idPost) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE products ADD CONSTRAINT products_ibfk_1 FOREIGN KEY (owner) REFERENCES users (idUser) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE productsevaluations ADD CONSTRAINT productsevaluations_ibfk_1 FOREIGN KEY (product) REFERENCES products (idProduct) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE productsevaluations ADD CONSTRAINT productsevaluations_ibfk_2 FOREIGN KEY (evaluator) REFERENCES users (idUser) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reportedposts ADD CONSTRAINT reportedposts_ibfk_1 FOREIGN KEY (idPost) REFERENCES posts (idPost) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE savedposts ADD CONSTRAINT savedposts_ibfk_1 FOREIGN KEY (owner) REFERENCES users (idUser) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE savedposts ADD CONSTRAINT savedposts_ibfk_2 FOREIGN KEY (post) REFERENCES posts (idPost) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE souscarts ADD CONSTRAINT fk_Cart FOREIGN KEY (idCart) REFERENCES carts (idCarts) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE souscarts ADD CONSTRAINT fk_product FOREIGN KEY (id_product) REFERENCES products (idProduct) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE notification_new_course DROP FOREIGN KEY FK_1576F2B189843FC4');
        $this->addSql('ALTER TABLE notification_new_course DROP FOREIGN KEY FK_1576F2B17B860482');
        $this->addSql('DROP TABLE notification_new_course');
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE carts DROP FOREIGN KEY FK_4E004AACCF60E67C');
        $this->addSql('ALTER TABLE carts CHANGE owner owner INT NOT NULL, CHANGE isconfirmed isConfirmed TINYINT(1) DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE carts ADD CONSTRAINT carts_ibfk_1 FOREIGN KEY (owner) REFERENCES users (idUser) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE carts RENAME INDEX idx_4e004aaccf60e67c TO idUser');
        $this->addSql('ALTER TABLE collects DROP FOREIGN KEY FK_A17AFF6CCEDBF54C');
        $this->addSql('ALTER TABLE collects DROP FOREIGN KEY FK_A17AFF6C739B3C63');
        $this->addSql('ALTER TABLE collects CHANGE collector collector INT NOT NULL, CHANGE collectsdate collectsDate DATE DEFAULT \'current_timestamp()\' NOT NULL, CHANGE collectsPts collectsPts INT NOT NULL');
        $this->addSql('ALTER TABLE collects ADD CONSTRAINT collectsPts FOREIGN KEY (collectsPts) REFERENCES collectspts (idCollectsPts) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE collects ADD CONSTRAINT collector FOREIGN KEY (collector) REFERENCES users (idUser) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE collects RENAME INDEX idx_a17aff6c739b3c63 TO idCollectsPts');
        $this->addSql('ALTER TABLE collects RENAME INDEX idx_a17aff6ccedbf54c TO idUser');
        $this->addSql('ALTER TABLE collectspts CHANGE capacity capacity DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE coursefeedbacks DROP FOREIGN KEY FK_F91817BF169E6FB9');
        $this->addSql('ALTER TABLE coursefeedbacks DROP FOREIGN KEY FK_F91817BFCF60E67C');
        $this->addSql('ALTER TABLE coursefeedbacks CHANGE course course INT NOT NULL, CHANGE owner owner INT NOT NULL, CHANGE content content TEXT NOT NULL, CHANGE posteddate postedDate DATE DEFAULT \'current_timestamp()\' NOT NULL');
        $this->addSql('ALTER TABLE coursefeedbacks ADD CONSTRAINT feedbackOwner FOREIGN KEY (owner) REFERENCES users (idUser) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE coursefeedbacks ADD CONSTRAINT courseRated FOREIGN KEY (course) REFERENCES courses (idCourse) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE coursefeedbacks RENAME INDEX idx_f91817bfcf60e67c TO idUser');
        $this->addSql('ALTER TABLE coursefeedbacks RENAME INDEX idx_f91817bf169e6fb9 TO idCourse');
        $this->addSql('ALTER TABLE courseparticipations DROP FOREIGN KEY FK_911BC21F169E6FB9');
        $this->addSql('ALTER TABLE courseparticipations DROP FOREIGN KEY FK_911BC21FD79F6B11');
        $this->addSql('ALTER TABLE courseparticipations CHANGE course course INT NOT NULL, CHANGE participant participant INT NOT NULL, CHANGE participationdate participationDate DATE DEFAULT \'current_timestamp()\' NOT NULL, CHANGE sectiondone sectionDone INT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE courseparticipations ADD CONSTRAINT courseP FOREIGN KEY (course) REFERENCES courses (idCourse) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE courseparticipations ADD CONSTRAINT participantCourse FOREIGN KEY (participant) REFERENCES users (idUser) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE courseparticipations RENAME INDEX idx_911bc21fd79f6b11 TO idUser');
        $this->addSql('ALTER TABLE courseparticipations RENAME INDEX idx_911bc21f169e6fb9 TO course');
        $this->addSql('ALTER TABLE courses DROP FOREIGN KEY FK_A9A55A4C99074648');
        $this->addSql('ALTER TABLE courses CHANGE tutor tutor INT NOT NULL, CHANGE description description TEXT NOT NULL, CHANGE posteddate postedDate DATE DEFAULT \'current_timestamp()\' NOT NULL, CHANGE nbrregistred nbrRegistred INT DEFAULT 0 NOT NULL, CHANGE rate rate DOUBLE PRECISION DEFAULT \'0\' NOT NULL, CHANGE nbrpersonrated nbrPersonRated INT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE courses ADD CONSTRAINT tutorId FOREIGN KEY (tutor) REFERENCES users (idUser) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE courses RENAME INDEX idx_a9a55a4c99074648 TO tutor');
        $this->addSql('ALTER TABLE creditcards DROP FOREIGN KEY FK_5E2597DBCF60E67C');
        $this->addSql('ALTER TABLE creditcards CHANGE owner owner INT NOT NULL');
        $this->addSql('ALTER TABLE creditcards ADD CONSTRAINT ownerCard FOREIGN KEY (owner) REFERENCES users (idUser) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE creditcards RENAME INDEX idx_5e2597dbcf60e67c TO owner');
        $this->addSql('ALTER TABLE quizanswers DROP FOREIGN KEY FK_44A7D1DC7C77973D');
        $this->addSql('ALTER TABLE quizanswers DROP FOREIGN KEY FK_44A7D1DCB723AF33');
        $this->addSql('ALTER TABLE quizanswers CHANGE quizz quizz INT NOT NULL, CHANGE student student INT NOT NULL, CHANGE answerdate answerDate DATETIME DEFAULT \'current_timestamp()\' NOT NULL');
        $this->addSql('ALTER TABLE quizanswers ADD CONSTRAINT idUser FOREIGN KEY (student) REFERENCES users (idUser) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE quizanswers ADD CONSTRAINT idQuizz FOREIGN KEY (quizz) REFERENCES quizzes (idQuiz) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE quizanswers RENAME INDEX idx_44a7d1dcb723af33 TO idUser');
        $this->addSql('ALTER TABLE quizanswers RENAME INDEX idx_44a7d1dc7c77973d TO idQuizz');
        $this->addSql('ALTER TABLE quizquestions DROP FOREIGN KEY FK_72D8E8F0A412FA92');
        $this->addSql('ALTER TABLE quizquestions CHANGE quiz quiz INT NOT NULL, CHANGE question question TEXT NOT NULL, CHANGE choice_1 choice_1 TEXT NOT NULL, CHANGE choice_2 choice_2 TEXT NOT NULL, CHANGE choice_3 choice_3 TEXT NOT NULL, CHANGE choice_4 choice_4 TEXT NOT NULL');
        $this->addSql('ALTER TABLE quizquestions ADD CONSTRAINT quiz FOREIGN KEY (quiz) REFERENCES quizzes (idQuiz) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE quizquestions RENAME INDEX idx_72d8e8f0a412fa92 TO quiz');
        $this->addSql('ALTER TABLE quizzes DROP FOREIGN KEY FK_94DC9FB52D737AEF');
        $this->addSql('ALTER TABLE quizzes CHANGE section section INT NOT NULL');
        $this->addSql('ALTER TABLE quizzes ADD CONSTRAINT idSection FOREIGN KEY (section) REFERENCES sections (idSection) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE quizzes RENAME INDEX idx_94dc9fb52d737aef TO idSection');
        $this->addSql('ALTER TABLE sections DROP FOREIGN KEY FK_2B964398169E6FB9');
        $this->addSql('ALTER TABLE sections CHANGE course course INT NOT NULL, CHANGE description description TEXT NOT NULL, CHANGE posteddate postedDate DATETIME DEFAULT \'current_timestamp()\' NOT NULL');
        $this->addSql('ALTER TABLE sections ADD CONSTRAINT idCourse FOREIGN KEY (course) REFERENCES courses (idCourse) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sections RENAME INDEX idx_2b964398169e6fb9 TO idCourse');
        $this->addSql('ALTER TABLE users CHANGE isactive isActive TINYINT(1) DEFAULT 1 NOT NULL, CHANGE nbrptscollects nbrPtsCollects INT DEFAULT 0 NOT NULL');
    }
}
