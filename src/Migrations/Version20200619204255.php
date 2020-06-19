<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200619204255 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE meal ADD moment_id INT DEFAULT NULL, ADD user_id INT NOT NULL, ADD type_id INT DEFAULT NULL, ADD month_id INT NOT NULL, ADD year_id INT NOT NULL');
        $this->addSql('ALTER TABLE meal ADD CONSTRAINT FK_9EF68E9CABE99143 FOREIGN KEY (moment_id) REFERENCES moment (id)');
        $this->addSql('ALTER TABLE meal ADD CONSTRAINT FK_9EF68E9CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE meal ADD CONSTRAINT FK_9EF68E9CC54C8C93 FOREIGN KEY (type_id) REFERENCES type (id)');
        $this->addSql('ALTER TABLE meal ADD CONSTRAINT FK_9EF68E9CA0CBDE4 FOREIGN KEY (month_id) REFERENCES month (id)');
        $this->addSql('ALTER TABLE meal ADD CONSTRAINT FK_9EF68E9C40C1FEA7 FOREIGN KEY (year_id) REFERENCES year (id)');
        $this->addSql('CREATE INDEX IDX_9EF68E9CABE99143 ON meal (moment_id)');
        $this->addSql('CREATE INDEX IDX_9EF68E9CA76ED395 ON meal (user_id)');
        $this->addSql('CREATE INDEX IDX_9EF68E9CC54C8C93 ON meal (type_id)');
        $this->addSql('CREATE INDEX IDX_9EF68E9CA0CBDE4 ON meal (month_id)');
        $this->addSql('CREATE INDEX IDX_9EF68E9C40C1FEA7 ON meal (year_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE meal DROP FOREIGN KEY FK_9EF68E9CABE99143');
        $this->addSql('ALTER TABLE meal DROP FOREIGN KEY FK_9EF68E9CA76ED395');
        $this->addSql('ALTER TABLE meal DROP FOREIGN KEY FK_9EF68E9CC54C8C93');
        $this->addSql('ALTER TABLE meal DROP FOREIGN KEY FK_9EF68E9CA0CBDE4');
        $this->addSql('ALTER TABLE meal DROP FOREIGN KEY FK_9EF68E9C40C1FEA7');
        $this->addSql('DROP INDEX IDX_9EF68E9CABE99143 ON meal');
        $this->addSql('DROP INDEX IDX_9EF68E9CA76ED395 ON meal');
        $this->addSql('DROP INDEX IDX_9EF68E9CC54C8C93 ON meal');
        $this->addSql('DROP INDEX IDX_9EF68E9CA0CBDE4 ON meal');
        $this->addSql('DROP INDEX IDX_9EF68E9C40C1FEA7 ON meal');
        $this->addSql('ALTER TABLE meal DROP moment_id, DROP user_id, DROP type_id, DROP month_id, DROP year_id');
    }
}
