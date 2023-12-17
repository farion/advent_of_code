package org.frieder.aoc.day17.lib;

import org.psjava.algo.graph.shortestpath.DijkstraAlgorithm;
import org.psjava.algo.graph.shortestpath.SingleSourceShortestPathResult;
import org.psjava.ds.graph.DirectedWeightedEdge;
import org.psjava.ds.graph.MutableDirectedWeightedGraph;
import org.psjava.ds.numbersystrem.IntegerNumberSystem;
import org.psjava.goods.GoodDijkstraAlgorithm;

import java.io.IOException;
import java.nio.file.Files;
import java.nio.file.Paths;
import java.util.ArrayList;
import java.util.Collection;
import java.util.List;
import java.util.Map;
import java.util.concurrent.atomic.AtomicInteger;
import java.util.function.BiFunction;
import java.util.stream.Collectors;
import java.util.stream.IntStream;

import static org.frieder.aoc.day17.lib.Direction.H;
import static org.frieder.aoc.day17.lib.Direction.V;

public class Solver {

    private Map<Coordinate, Integer> costMap;

    MutableDirectedWeightedGraph<String, Integer> graph = MutableDirectedWeightedGraph.create();

    private int maxX;
    private int maxY;

    private final List<BiFunction<Integer, Coordinate, Node>> costCallbacks = new ArrayList<>() {{
        add((i, c) -> new Node(new Coordinate(c.getX() - i, c.getY()), H));
        add((i, c) -> new Node(new Coordinate(c.getX() + i, c.getY()), H));
        add((i, c) -> new Node(new Coordinate(c.getX(), c.getY() - i), V));
        add((i, c) -> new Node(new Coordinate(c.getX(), c.getY() + i), V));
    }};

    public double getResult(String path, int minSlide, int maxSlide) throws IOException {

        AtomicInteger yln = new AtomicInteger();
        AtomicInteger xln = new AtomicInteger();
        this.costMap = Files.readAllLines(Paths.get(path)).stream()
                .filter(ln -> ln.trim().length() > 0)
                .map(ln -> {
                    int curY = yln.getAndIncrement();
                    xln.set(0);
                    return IntStream.range(0, ln.length())
                            .mapToObj(n -> Map.entry(new Coordinate(xln.getAndIncrement(), curY),
                                    Integer.parseInt(ln.substring(n, n + 1))))
                            .collect(Collectors.toList());
                })
                .flatMap(Collection::stream)
                .collect(Collectors.toMap(Map.Entry::getKey, Map.Entry::getValue, (a, b) -> a));


        this.maxX = xln.get() - 1;
        this.maxY = yln.get() - 1;

        // fill edges
        costMap.forEach((c, value) -> IntStream
                .range(minSlide, maxSlide + 1)
                .forEach(i -> this.costCallbacks
                        .forEach(cb -> this.setCost(cb.apply(i, c), c))
                )
        );

        graph.insertVertex("S");
        graph.insertVertex("T");
        graph.insertVertex(maxX + ":" + maxY + V);
        graph.insertVertex(maxX + ":" + maxY + H);

        graph.addEdge("S", "0:0" + H, 0);
        graph.addEdge("S", "0:0" + V, 0);
        graph.addEdge(maxX + ":" + maxY + V, "T", 0);
        graph.addEdge(maxX + ":" + maxY + H, "T", 0);

        // do some nice dijkstra
        IntegerNumberSystem NS = IntegerNumberSystem.getInstance();
        DijkstraAlgorithm dijkstra = GoodDijkstraAlgorithm.getInstance();
        SingleSourceShortestPathResult<String, Integer, DirectedWeightedEdge<String, Integer>> result = dijkstra.calc(graph, "S", NS);
        return result.getDistance("T");
    }

    private void setCost(Node prevNode, Coordinate c) {

        if (this.costMap.get(prevNode.getC()) == null)
            return;

        Coordinate pc = prevNode.getC();
        int prevX = pc.getX();
        int prevY = pc.getY();

        if (prevX == maxX && prevY == maxY)
            return;

        int x = c.getX();
        int y = c.getY();
        Direction prevD = prevNode.getD();

        String from = prevX + ":" + prevY + prevD;
        String to = x + ":" + y + (prevD == H ? V : H);

        int cost = 0;
        cost += (prevX == x && prevY < y) ? IntStream.range(prevY + 1, y + 1)
                .map(cy -> this.costMap.get(new Coordinate(x, cy)))
                .sum() : 0;
        cost += (prevX == x && prevY > y) ? IntStream.range(y, prevY)
                .map(cy -> this.costMap.get(new Coordinate(x, cy)))
                .sum() : 0;
        cost += (prevX < x && prevY == y) ? IntStream.range(prevX + 1, x + 1)
                .map(cx -> this.costMap.get(new Coordinate(cx, y)))
                .sum() : 0;
        cost += (prevX > x && prevY == y) ? IntStream.range(x, prevX)
                .map(cx -> this.costMap.get(new Coordinate(cx, y)))
                .sum() : 0;

        graph.insertVertex(from);
        graph.insertVertex(to);
        graph.addEdge(from, to, cost);
    }

}
