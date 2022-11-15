<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221109164359 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE joueur DROP FOREIGN KEY FK_FD71A9C5EDB3A7AE');
        $this->addSql('DROP INDEX IDX_FD71A9C5EDB3A7AE ON joueur');
        $this->addSql('ALTER TABLE joueur ADD equipe_id INT NOT NULL, DROP id_equipe_id, DROP roles, DROP equipe');
        $this->addSql('ALTER TABLE joueur ADD CONSTRAINT FK_FD71A9C56D861B89 FOREIGN KEY (equipe_id) REFERENCES equipe (id)');
        $this->addSql('CREATE INDEX IDX_FD71A9C56D861B89 ON joueur (equipe_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE joueur DROP FOREIGN KEY FK_FD71A9C56D861B89');
        $this->addSql('DROP INDEX IDX_FD71A9C56D861B89 ON joueur');
        $this->addSql('ALTER TABLE joueur ADD roles JSON NOT NULL, ADD equipe INT NOT NULL, CHANGE equipe_id id_equipe_id INT NOT NULL');
        $this->addSql('ALTER TABLE joueur ADD CONSTRAINT FK_FD71A9C5EDB3A7AE FOREIGN KEY (id_equipe_id) REFERENCES equipe (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_FD71A9C5EDB3A7AE ON joueur (id_equipe_id)');
    }
}
