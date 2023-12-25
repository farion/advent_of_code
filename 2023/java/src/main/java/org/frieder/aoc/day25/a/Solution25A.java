package org.frieder.aoc.day25.a;

import org.jgrapht.Graph;
import org.jgrapht.alg.StoerWagnerMinimumCut;
import org.jgrapht.graph.DefaultEdge;
import org.jgrapht.graph.DefaultUndirectedGraph;

import java.io.IOException;
import java.nio.file.Files;
import java.nio.file.Paths;
import java.util.Arrays;
import java.util.Set;
import java.util.regex.Pattern;

public class Solution25A {

    private static final Pattern PATTERN_EDGES = Pattern.compile("^([a-z]+): ([a-z ]+)$");

    public static double getResult(String path) throws IOException {
        return new Solution25A().getNonStaticResult(path);
    }

    private double getNonStaticResult(String path) throws IOException {

        Graph<String, DefaultEdge> g = new DefaultUndirectedGraph<>(DefaultEdge.class);

        Files.readAllLines(Paths.get(path)).stream()
                .filter(ln -> !ln.trim().isEmpty())
                .forEach(ln -> PATTERN_EDGES.matcher(ln)
                        .results()
                        .forEach(mr -> {
                            String src = mr.group(1);
                            g.addVertex(src);
                            Arrays.stream(mr.group(2).split(" "))
                                    .forEach(d -> {
                                        if (!d.trim().isBlank()) {
                                            g.addVertex(d);
                                            g.addEdge(src, d);
                                        }
                                    });
                        }));

        StoerWagnerMinimumCut<String, DefaultEdge> alg = new StoerWagnerMinimumCut<>(g);
        Set<String> cut = alg.minCut();

        return (g.vertexSet().size() - cut.size()) * cut.size();
    }
}