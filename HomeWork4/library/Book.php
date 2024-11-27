<?php

abstract class AbstractBook {
    protected string $name;
    protected array $authors;
    protected string $publisher;
    protected int $publishDate;
    protected int $count;

    public function __construct(string $name, array $authors, string $publisher, int $publishDate)
    {
        $this->name = $name;
        $this->authors = $authors;
        $this->publisher = $publisher;
        $this->publishDate = $publishDate;
        $this->count = 0;
    }

    protected function counting() {
        $this->count ++;
    }

    // Метод добавления книги и сопутсвующие при этом операции с ней
    abstract function addBook(): void;
    // Метод получения информации о нахождении книги
    abstract function getLocation(): string;
    // Метод получениия книги
    abstract function getBook(): void;
    // Метод получения количества запросов по книге
    abstract function getCount(): string;

}

// Местонахождение книги в библиотеке формируем исходя из следующей логики
// Агрегация:
// Конкретная полка в конкретном шкафу имеется. Её координаты (обьект) мы и передаем обьекту книга
// Композиция:
// Шкаф сам по себе просто так не будет гулять из помещения в помещение))) тем более в библиотеке)))
// Когда-то его поставили в конкретную комнату. Тем самым мы при создании этого обьекта (шкаф)
// строго указали его местонахождение - обьект (комнату)
class Closet {
    private int $shelfNumber;
    private int $сlosetfNumber;
    private Room $roomLocation;
    
    public function __construct(int $roomNumber, string $roomSector, int $сlosetfNumber, int $shelfNumber)
    {
        $this->roomLocation = new Room($roomNumber, $roomSector);
        $this->сlosetfNumber = $сlosetfNumber;
        $this->shelfNumber = $shelfNumber;
    }

    public function getLocation(): string {
        return  $this->roomLocation->getRoomLocation().
                ' Шкаф №'.$this->shelfNumber.
                ' Полка '.$this->сlosetfNumber;
    }
}

class Room {
    private int $roomNumber;
    private string $roomSector;
    
    public function __construct($roomNumber, $roomSector)
    {
        $this->roomNumber = $roomNumber;
        $this->roomSector = $roomSector;
    }

    public function getRoomLocation(): string {
        return  ' Комната №'.$this->roomNumber.
                ' Сектор - '.$this->roomSector;
    }
}

class PrintedEditionBook extends AbstractBook {
    private Closet $closet;

    public function __construct(string $name, array $authors, string $publisher, int $publishDate, Closet $closet)
    {
        parent::__construct($name, $authors, $publisher, $publishDate);
        $this->closet = $closet;
        $this->addBook();
    }

    public function addBook(): void {
        echo 'Книга '.$this->name.' добавлена в библиотеку'.PHP_EOL;
    }

    public function getLocation(): string {
        
        return 'Местонахождение книги '.$this->name.$this->closet->getLocation().PHP_EOL;
    }

    public function getBook(): void {
        $location = $this->getLocation();
        echo 'Возьмите книгу и проследуйте с ней к регистратору'.PHP_EOL.$location;
        $this->counting();
    }

    public function getCount(): string {
        return 'Количество запросов на получение книги - '.$this->count.PHP_EOL;
    }

}

class DigitalEditionBook extends AbstractBook {
    private string $wgetURL;

    public function __construct(string $name, array $authors, string $publisher, int $publishDate, string $wgetURL)
    {
        parent::__construct($name, $authors, $publisher, $publishDate);
        $this->wgetURL = $wgetURL;
        $this->addBook();
    }

    public function addBook(): void {
        echo 'Ссылка для скачивания книги '.$this->name.' добавлена в библиотеку'.PHP_EOL;
    }

    public function getLocation(): string {
        return 'Ссылка для скачивания книги '.$this->name.' '.$this->wgetURL;
    }

    public function getBook(): void {
        $location = $this->getLocation();
        $this->counting();
        echo 'Вы можете скачать книгу по ссылке'.PHP_EOL.$location;
    }

    public function getCount(): string {
        return 'Количество запросов на скачивание книги - '.$this->count.PHP_EOL;
    }

}

$closet1 = new Closet(5, 'A', 4, 5);
$closet2 = new Closet(5, 'A', 4, 6);
$printedBook = new PrintedEditionBook('PrintedBook', ['author1', 'author2'], 'Publisher22', 2020, $closet1);
$digitalBook = new DigitalEditionBook('DigitalBook', ['author1'], 'Publisher55', 2024, 'https://www.google.com/');

echo $printedBook->getBook().PHP_EOL;
echo $printedBook->getBook().PHP_EOL;
echo $printedBook->getBook().PHP_EOL;
echo $printedBook->getBook().PHP_EOL;
echo $digitalBook->getBook().PHP_EOL;
echo $digitalBook->getBook().PHP_EOL;

echo $printedBook->getCount();
echo $digitalBook->getCount();


// var_dump($printedBook);
// var_dump($digitalBook);
