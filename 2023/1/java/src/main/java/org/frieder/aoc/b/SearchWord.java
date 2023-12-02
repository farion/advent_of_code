package org.frieder.aoc.b;

import lombok.Getter;

import java.util.Arrays;
import java.util.List;
import java.util.regex.Pattern;
import java.util.stream.Collectors;

@Getter
class SearchWord {
    private final int result;
    private final Pattern pattern;

    public SearchWord(int result, String value) {
        this.result = result;
        this.pattern = Pattern.compile(value != null ? value : String.valueOf(result));
    }

    public static List<SearchWord> fromArray(String[][] array) {
        return Arrays.stream(array)
                .map(a -> new SearchWord(Integer.parseInt(a[0]), (a.length > 1) ? a[1] : null))
                .collect(Collectors.toList());
    }

}
