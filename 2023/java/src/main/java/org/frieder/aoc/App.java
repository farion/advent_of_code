package org.frieder.aoc;

import org.apache.commons.cli.CommandLine;
import org.apache.commons.cli.CommandLineParser;
import org.apache.commons.cli.DefaultParser;
import org.apache.commons.cli.Options;

public class App {
    public static void main(String[] args) {

        Options options = new Options();
        options.addOption("d", "day", true, "Set the day");
        options.addOption("t", "task", true, "Task of day a or b");
        options.addOption("i", "input", true, "Specify input file");

        CommandLineParser parser = new DefaultParser();
        try {
            CommandLine cmd = parser.parse(options, args);
            int day = Integer.parseInt(cmd.getOptionValue("day"));
            Task task = Task.valueOf(cmd.getOptionValue("task"));

            String input = "../resources/" + day + "/input.txt";
            if (cmd.hasOption("input")) {
                input = cmd.getOptionValue("input");
            }

            Class<?> clazz = Class.forName("org.frieder.aoc.day" + day + "." + task.name().toLowerCase() + ".Solution" + day + task.name().toUpperCase());

            Object result = clazz.getMethod("getResult", String.class).invoke(null, input);

            System.out.printf("%.0f\n", (double) result);

        } catch (Exception e) {
            e.printStackTrace();
            System.exit(1);
        }
    }
}