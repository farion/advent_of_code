package org.frieder.aoc;

import org.frieder.aoc.a.SolutionA;
import org.frieder.aoc.b.SolutionB;

import java.io.IOException;

public class App {
    public static void main(String[] args) throws IOException {

        Task task = Task.valueOf(args[0]);
        String inputFile = args[1];
        int result = 0;

        switch (task){
            case a:
                result = SolutionA.getResult(inputFile);
                break;
            case b:
                result = SolutionB.getResult(inputFile);
                break;
        }

        System.out.println("Result: " + result);
    }




}