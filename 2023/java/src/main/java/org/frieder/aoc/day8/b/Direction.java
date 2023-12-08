package org.frieder.aoc.day8.b;

import lombok.AllArgsConstructor;
import lombok.Getter;

@AllArgsConstructor
@Getter
public class Direction {

    private String left;

    private String right;

    public String getByDirectionChar(char i) {
        if(i == 'L'){
            return left;
        }else if(i == 'R'){
            return right;
        }else {
            throw new RuntimeException();
        }
    }
}
