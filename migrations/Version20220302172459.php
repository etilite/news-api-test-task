<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220302172459 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
	$this->addSql("UPDATE tag SET name='экономика' WHERE name='#экономика'"); 
	$this->addSql("UPDATE tag SET name='наука' WHERE name='#наука'");
	$this->addSql("UPDATE tag SET name='политика' WHERE name='#политика'");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
	$this->addSql("UPDATE tag SET name='#экономика' WHERE name='экономика'");
        $this->addSql("UPDATE tag SET name='#наука' WHERE name='наука'");
        $this->addSql("UPDATE tag SET name='#политика' WHERE name='политика'");

    }
}
