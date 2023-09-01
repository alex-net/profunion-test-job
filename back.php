<?php
/**
 * Класс для работы с графом
 */
class MinGraph
{
    /**
     * данные по рёбрам и вершинам графа
     */
    const GRAPH_DATA = [
        1 => [2],
        2 => [3, 4, 6],
        3 => [2, 4, 5],
        4 => [2, 3, 5, 6],
        5 => [3, 4, 6],
        6 => [2, 4, 5]
    ];

    /**
     * Данные по длинам рёбер графа.
     * Заполняется случайными числами от 1 до 50
     */
    private $graphLens;

    /**
     * Найденные варианты прохождения пути от точки к точке по графу
     */
    private $paths = [];

    /**
     * инициализация
     * @param int $from  Начальная точка пути
     */
    public function __construct()
    {
        $this->graphLens = [];
        foreach (static::GRAPH_DATA as $pointS => $pointList) {
            foreach ($pointList as $pointD) {
                if (!isset($this->graphLens[$pointS . '-' . $pointD])) {
                    $this->graphLens[$pointS . '-' . $pointD] = $this->graphLens[$pointD . '-' . $pointS] = rand(1, 50);
                }
            }
        }
    }
    /**
     * Печать нагенеренных данных по графу ...
     */
    public function print()
    {
        foreach (static::GRAPH_DATA as $pointS => $pointList) {
            $lens = [];
            foreach ($pointList as $pointD) {
                $lens[] = $pointS . '->' . $pointD . ' = ' . $this->graphLens[$pointS . '-' . $pointD];
            }
            echo $pointS .': '. implode('; ',$lens) . "\n";
        }
    }


    /**
     * Определение длины всех путуй до точки $to и выбор минимального ...
     *
     * @param      int       $from   Начальная точка в пути по графу
     * @param      int       $to     конечная точка в пути по графу
     * @param      string    $path   пройденный путь ..
     *
     * @return     array      Набор вершин, через которые проходит минимальный путь
     */
    public function findMinPath($from, $to, $path = '')
    {
        if ($from == $to) {
            return [0];
        }
        $path = $path ?: $from;

        // Рекурсивно проходим по графу .. собираем все варианты путей .. от вершины $from до вершины $to
        foreach (static::GRAPH_DATA[$from] as $point) {
            if (strpos($path, (string)$point) !== false) {
                continue;
            }

            if (empty($this->paths[$path . $point])) {
                $this->paths[$path . $point] = $this->paths[$path] ?? 0;
            }
            $this->paths[$path . $point] += $this->graphLens["$from-$point"];

            if ($to != $point) {
                $this->findMinPath($point, $to, $path . $point);
            }
        }
        // удаляем путь, который не содержит конечную вершину $to
        unset($this->paths[$path]);

        // В конце обхода .. определяем минимальный путь
        if ($path == $from) {
            $minPath = array_search(min($this->paths), $this->paths);
            $arr = str_split($minPath);
            array_unshift($arr, $this->paths[$minPath]);
            return $arr;
        }
    }
}

$gr = new MinGraph();
echo "Длины рёбер графа представлены следующим списком:\n";
$gr->print();

$from = 1; $to = 6;
$path = $gr->findMinPath($from, $to);
$len = array_shift($path);
echo "Длина кратчайшего пути из вершины $from в вершину $to = $len; Путь проходит через вершины: " . implode(' -> ', $path). "\n";
