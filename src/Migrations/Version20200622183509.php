<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200622183509 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE day ADD month_id INT NOT NULL, ADD year_id INT NOT NULL');
        $this->addSql('ALTER TABLE day ADD CONSTRAINT FK_E5A02990A0CBDE4 FOREIGN KEY (month_id) REFERENCES month (id)');
        $this->addSql('ALTER TABLE day ADD CONSTRAINT FK_E5A0299040C1FEA7 FOREIGN KEY (year_id) REFERENCES year (id)');
        $this->addSql('CREATE INDEX IDX_E5A02990A0CBDE4 ON day (month_id)');
        $this->addSql('CREATE INDEX IDX_E5A0299040C1FEA7 ON day (year_id)');
        $this->addSql('ALTER TABLE meal DROP FOREIGN KEY FK_9EF68E9C40C1FEA7');
        $this->addSql('ALTER TABLE meal DROP FOREIGN KEY FK_9EF68E9CA0CBDE4');
        $this->addSql('DROP INDEX IDX_9EF68E9C40C1FEA7 ON meal');
        $this->addSql('DROP INDEX IDX_9EF68E9CA0CBDE4 ON meal');
        $this->addSql('ALTER TABLE meal DROP month_id, DROP year_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE day DROP FOREIGN KEY FK_E5A02990A0CBDE4');
        $this->addSql('ALTER TABLE day DROP FOREIGN KEY FK_E5A0299040C1FEA7');
        $this->addSql('DROP INDEX IDX_E5A02990A0CBDE4 ON day');
        $this->addSql('DROP INDEX IDX_E5A0299040C1FEA7 ON day');
        $this->addSql('ALTER TABLE day DROP month_id, DROP year_id');
        $this->addSql('ALTER TABLE meal ADD month_id INT NOT NULL, ADD year_id INT NOT NULL');
        $this->addSql('ALTER TABLE meal ADD CONSTRAINT FK_9EF68E9C40C1FEA7 FOREIGN KEY (year_id) REFERENCES year (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE meal ADD CONSTRAINT FK_9EF68E9CA0CBDE4 FOREIGN KEY (month_id) REFERENCES month (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_9EF68E9C40C1FEA7 ON meal (year_id)');
        $this->addSql('CREATE INDEX IDX_9EF68E9CA0CBDE4 ON meal (month_id)');
    }
}
