<?php

namespace Core;

class JoinBuilder
{
    private array $conditions = [];
    private array $bindings = [];
    private static int $bindingCounter = 0;

    /**
     * Add a join condition
     */
    public function on(string $column, string $operator, $value): self
    {
        if (is_string($value) && !str_contains($value, '.') && !str_starts_with($value, ':')) {
            // It's a literal value, create a binding
            $placeholder = ':join_' . ++self::$bindingCounter;
            $this->bindings[$placeholder] = $value;
            $this->conditions[] = "{$column} {$operator} {$placeholder}";
        } else {
            // It's a column reference or already a placeholder
            $this->conditions[] = "{$column} {$operator} {$value}";
        }
        
        return $this;
    }

    /**
     * Add an AND condition
     */
    public function and(string $column, string $operator, $value): self
    {
        if (!empty($this->conditions)) {
            $this->conditions[] = 'AND';
        }
        return $this->on($column, $operator, $value);
    }

    /**
     * Add an OR condition
     */
    public function or(string $column, string $operator, $value): self
    {
        if (!empty($this->conditions)) {
            $this->conditions[] = 'OR';
        }
        return $this->on($column, $operator, $value);
    }

    /**
     * Add a nested condition group
     */
    public function where(callable $callback): self
    {
        $nestedBuilder = new self();
        $callback($nestedBuilder);
        
        $this->conditions[] = '(' . $nestedBuilder->toSql() . ')';
        $this->bindings = array_merge($this->bindings, $nestedBuilder->getBindings());
        
        return $this;
    }

    /**
     * Convert to SQL string
     */
    public function toSql(): string
    {
        return implode(' ', $this->conditions);
    }

    /**
     * Get parameter bindings
     */
    public function getBindings(): array
    {
        return $this->bindings;
    }
}
