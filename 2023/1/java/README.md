# Day 1

## Compile

```shell
mvn clean install
```

## Manual Tests
```shell
# Test Data A
java -jar target/aoc-2023-1.0-SNAPSHOT-jar-with-dependencies.jar a ../test_a.txt

# Result B
java -jar target/aoc-2023-1.0-SNAPSHOT-jar-with-dependencies.jar a ../input.txt

# Test Data B
java -jar target/aoc-2023-1.0-SNAPSHOT-jar-with-dependencies.jar b ../test_b.txt

# Result B
java -jar target/aoc-2023-1.0-SNAPSHOT-jar-with-dependencies.jar b ../input.txt
```

## Run UnitTests

```shell
mvn test
```