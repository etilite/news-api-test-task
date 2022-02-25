<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220225162006 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
	$this->addSql("INSERT INTO tag (name) VALUES ('#экономика')");
	$this->addSql("INSERT INTO tag (name) VALUES ('#наука')");
	$this->addSql("INSERT INTO tag (name) VALUES ('#политика')");
	$this->addSql("INSERT INTO news (title, text, photo, published_at) VALUES ('Новость 1', 'Текст 1', 'picture1.jpg', '2020-01-01 11:11:11')");
	$this->addSql("INSERT INTO news (title, text, photo, published_at) VALUES ('Новость 2', 'Текст 2', 'picture2.jpg', '2020-02-01 11:11:11')");
	$this->addSql("INSERT INTO news (title, text, photo, published_at) VALUES ('Новость 3', 'Текст 3', 'picture3.jpg', '2021-05-01 11:11:11')");
	$this->addSql("INSERT INTO news (title, text, photo, published_at) VALUES ('Новость 4', 'Текст 4', 'picture4.jpg', '2021-05-01 11:11:11')");
	$this->addSql("INSERT INTO news (title, text, photo, published_at) VALUES ('Новость 5', 'Текст 5', 'picture5.jpg', '2022-01-01 11:11:11')");
	$this->addSql("INSERT INTO news (title, text, photo, published_at) VALUES ('Новость 6', 'Текст 6', 'picture6.jpg', '2022-02-01 11:11:11')");
	$this->addSql("INSERT INTO news (title, text, photo, published_at) VALUES ('Новость 7', 'Текст 7', 'picture7.jpg', '2022-02-02 11:11:11')");
	$this->addSql("INSERT INTO tag_news VALUES ('1', '1')");
	$this->addSql("INSERT INTO tag_news VALUES ('1', '2')");
	$this->addSql("INSERT INTO tag_news VALUES ('1', '3')");
	$this->addSql("INSERT INTO tag_news VALUES ('1', '4')");
	$this->addSql("INSERT INTO tag_news VALUES ('1', '5')");
	$this->addSql("INSERT INTO tag_news VALUES ('1', '6')");
	$this->addSql("INSERT INTO tag_news VALUES ('2', '1')");
	$this->addSql("INSERT INTO tag_news VALUES ('2', '2')");
	$this->addSql("INSERT INTO tag_news VALUES ('2', '3')");
	$this->addSql("INSERT INTO tag_news VALUES ('2', '7')");
	$this->addSql("INSERT INTO tag_news VALUES ('3', '4')");
	$this->addSql("INSERT INTO tag_news VALUES ('3', '5')");
	$this->addSql("INSERT INTO tag_news VALUES ('3', '6')");
	$this->addSql("INSERT INTO tag_news VALUES ('3', '7')");
	$this->addSql("INSERT INTO tag_news VALUES ('3', '1')");

    }

    public function down(Schema $schema): void
    {
	    // this down() migration is auto-generated, please modify it to your needs
	    $this->addSql("TRUNCATE TABLE tag_news");
	    $this->addSql("TRUNCATE TABLE news");
	    $this->addSql("TRUNCATE TABLE tag");

    }
}
