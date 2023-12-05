package org.frieder.aoc.day5.a;

import java.io.IOException;
import java.nio.file.Files;
import java.nio.file.Paths;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;
import java.util.regex.Pattern;
import java.util.stream.Collectors;

public class Solution5A {

    private static final Pattern PATTERN_SEEDS = Pattern.compile("^seeds:.*$");
    private static final Pattern PATTERN_NUMBERS = Pattern.compile("([0-9]+)");
    private static final Pattern PATTERN_CATEGORY = Pattern.compile("^([a-z]+)-to-([a-z]+).*$");
    private String currentSource;
    private String currentTarget;
    private final Map<String, Data> data = new HashMap<>();
    private List<Double> seeds;

    public static double getResult(String path) throws IOException {
        return new Solution5A().getNonStaticResult(path);
    }

    private Double getNonStaticResult(String path) throws IOException {
        Files.readAllLines(Paths.get(path)).stream()
                .filter(ln -> ln.trim().length() > 0)
                .forEach(this::lineToData);

        return this.seeds.stream()
                .map(seed -> this.getTarget("seed", "location", seed))
                .mapToDouble(Double::doubleValue)
                .min()
                .orElseThrow();
    }

    private void lineToData(String ln) {
        if (PATTERN_SEEDS.matcher(ln).matches()) {
            this.seeds = PATTERN_NUMBERS.matcher(ln)
                    .results()
                    .map(mr -> Double.parseDouble(mr.group(1)))
                    .collect(Collectors.toList());
        } else if (PATTERN_CATEGORY.matcher(ln).matches()) {
            PATTERN_CATEGORY.matcher(ln)
                    .results()
                    .forEach(mr -> {
                        this.currentSource = mr.group(1);
                        this.currentTarget = mr.group(2);
                        this.data.put(this.currentSource, new Data(
                                this.currentSource,
                                this.currentTarget,
                                new ArrayList<>()));
                    });
        } else {
            List<Double> mappingNumbers = PATTERN_NUMBERS.matcher(ln)
                    .results()
                    .map(mr -> Double.parseDouble(mr.group(0)))
                    .collect(Collectors.toList());

            this.data.get(this.currentSource).getMappings().add(new Mapping(
                    mappingNumbers.get(0),
                    mappingNumbers.get(1),
                    mappingNumbers.get(2)));
        }
    }

    private Double getTarget(String source, String target, Double initial) {
        Double destination = this.data.get(source).getMappings()
                .stream()
                .filter(m -> initial >= m.getSourceStart() && initial < m.getSourceStart() + m.getRange())
                .findFirst()
                .map(m -> m.getDestinationStart() + (initial - m.getSourceStart()))
                .orElse(initial);

        return (target.equals(this.data.get(source).getTarget()))
                ? destination
                : this.getTarget(this.data.get(source).getTarget(), target, destination);
    }
}
