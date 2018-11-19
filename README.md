# Sequence Bundle

## Documentation
* [Installation](#installation)
* [Usage](#usage)
  - [Format](#format)
  - [Using Sequence Manager](#SequenceManager)
  - [Reset Counter](#ResetCounter)

### Installation
#### Step 1: Download bundle
If your project already uses Symfony Flex, execute this command to download, register and configure the bundle automatically:

```bash
composer require indragunawan/sequence-bundle
```

If you install without using Symfony Flex, first add the bundle by using composer then enable the bundle by adding `new Indragunawan\SequenceBundle\IndragunawanSequenceBundle()` to the list of registered bundles in the app/AppKernel.php file of your project

#### Step 2: Create your Sequence class

```php
<?php
// src/App/Entity/Sequence.php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Indragunawan\SequenceBundle\Model\Sequence as BaseSequence;

/**
* @ORM\Entity(repositoryClass="App\Repository\SequenceRepository")
*/
class Sequence extends BaseSequence
{
    /**
    * @ORM\Id()
    * @ORM\GeneratedValue(strategy="IDENTITY")
    * @ORM\Column(type="integer")
    */
    private $id;

    public function getId()
    {
        return $this->id;
    }
}
```

#### Step 3: Configure the bundle

```yaml
# app/config/config.yml
indragunawan_sequence:
orm:
    class: App\Entity\Sequence
    manager_name: default #if not set then the default value is 'default'
```

#### Step 4: Update your database schema

```bash
$ php bin/console doctrine:schema:update --force
```

### Usage

#### Insert your sequence manually to sequence table in database

| Field | Data Type | Required | Properties | Description |
| ----- | --------- | -------- | ---------- | ----------- |
| name | string | true | unique | Sequence name that use while you call in service manager |
| format | string | false | - | The format of text or string you want to be generate by service manager |
| last_value | int | false | - | The last value of the sequence |
| start_value | int | true | - | The start value for the sequence |
| increment_by | int | true | - | The increment of sequence value |
| last_reset | datetime | false | - | The date and time that sequence last restart |

### Format
#### For format you can see table below:

| Format | Value | Result |
| ------ | ------ | --- |
| null | 1 | 1 |
| '' | 2 | 2 |
| {{NUMBER\|3\|0}} | 3 | 003 |
| {{ABC}} | ['ABC' => 'abcVal'] | abcVal |
| {{NUMBER\|5\|A}} | 4 | AAAA4 |
| {{NUMBER\|5\|A\|0}} | 5 | AAAA5 |
| {{NUMBER\|5\|A\|1}} | 6 | 6AAAA |
| {{NUMBER\|5\|A\|2}} | 7 | AA7AA |
| {{NUMBER\|lower\|5\|A}} | 8 | aaaa8 |
| {{NUMBER\|ucfirst\|5\|abc}} | 9 | Abca9 |
| {{NUMBER\|upper\|5\|a\|2}} | 8 | AA8AA |
| {{y}} | - | date('y') |
| {{m}} | - | date('m') |
| {{d}} | - | date('d') |

#### Reserved Placeholder
* NUMBER (an integer contains sequence counter).
* php date format [See this](http://php.net/manual/en/function.date.php).
* Rj, Rn, Ry, RY, Rg for Roman numeral of date format.

#### Example

| format | value | result |
| --- | --- | --- |
| INV/{{NUMBER\|3\|0}}/{{ABC}} | 1, ['ABC'=>'TEST'] | INV/001/TEST |
| INV/{{NUMBER\|3\|0}} | 1 | INV/001 |
| INV/{{NUMBER\|4\|A}} | 1 | INV/AAA1 |

another example can be see in ``Tests/Utils/PlaceholderReplacerTest.php``

### SequenceManager

#### NOTE: Sequence manager can only be use inside doctrine transaction

### Call in Controller
```php

// use Indragunawan\SequenceBundle\Services\SequenceManager
$sequenceManager = $this->get(SequenceManager::class);
$em->transactional(function () use ($entity) {
  $entity->setSeqNum($sequenceManager->getNextValue('sequence_name'));
});
```

### Call in Event Listener
```php
// use Indragunawan\SequenceBundle\Services\SequenceManagerInterface
// Inject SequenceManagerInterface on constructor

public function prePersist(LifecycleEventArgs $args)
{
    $entity = $args->getObject();
    if ($entity instanceof EntityClass) {
        $args->getObjectManager()->transactional(function () use ($entity) {
            $entity->setSeqNum($this->sequenceManager->getNextValue('sequence_name'));
        });
    }
}
```

### ResetCounter
```bash
$ php bin/console indragunawan:sequence:reset-counter sequence_name
```
You can put this command into Crontab for periodically restart the counter.

## License
This bundle is under the MIT license. See the complete [license](LICENSE)
