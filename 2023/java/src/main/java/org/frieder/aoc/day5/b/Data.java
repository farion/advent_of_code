package org.frieder.aoc.day5.b;

import lombok.AllArgsConstructor;
import lombok.Getter;

import java.util.List;

@AllArgsConstructor
@Getter
public class Data {

    private String source;
    private String target;
    private List<Mapping> mappings;

}
