package org.frieder.aoc.day6.b;

import java.io.IOException;
import java.nio.file.Files;
import java.nio.file.Paths;
import java.util.List;
import java.util.regex.Pattern;
import java.util.stream.Collectors;

import static java.util.stream.Collectors.collectingAndThen;

public class Solution6B {

    private static final Pattern PATTERN_NUMBERS = Pattern.compile("([0-9]+)");

    public static double getResult(String path) throws IOException {
        return Files.readAllLines(Paths.get(path)).stream()
                .filter(ln -> ln.trim().length() > 0)
                .map(ln -> PATTERN_NUMBERS.matcher(ln.replaceAll(" ", ""))
                        .results()
                        .map(mr -> Double.parseDouble(mr.group(1)))
                        .collect(Collectors.toList()))
                .collect(collectingAndThen(
                        Collectors.toList(),
                        d -> List.of(new Race(d.get(0).get(0), d.get(1).get(0)))
                ))
                .stream()
                .map(Solution6B::calculateCubicEquationToGetAwesomePerformanceIncrease)
                .findFirst()
                .orElse(0D);
    }

    private static Double calculateCubicEquationToGetAwesomePerformanceIncrease(Race r) {
        double a = -1;
        double b = r.getTime();
        double c = -r.getDistance();

        double x1 = (-b + Math.sqrt(Math.pow(b, 2) - (4 * a * c))) / (2 * a);
        double x2 = (-b - Math.sqrt(Math.pow(b, 2) - (4 * a * c))) / (2 * a);

        return Math.floor(x2) - Math.ceil(x1) + 1;
    }
}
