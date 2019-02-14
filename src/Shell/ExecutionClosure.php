<?php

namespace BeyondCode\LaravelTinkerServer\Shell;

use Psy\Shell;
use Psy\Exception\BreakException;
use Psy\Exception\ErrorException;
use Psy\Exception\ThrowUpException;
use Psy\Exception\TypeErrorException;
use Psy\ExecutionClosure as BaseExecutionClosure;

class ExecutionClosure extends BaseExecutionClosure
{
    public function __construct(Shell $__psysh__, $__line__)
    {
        $this->setClosure($__psysh__, function () use ($__psysh__, $__line__) {
            try {
                try {
                    // Restore execution scope variables
                    \extract($__psysh__->getScopeVariables(false), EXTR_SKIP);

                    $__psysh__->addCode($__line__);

                    // Convert all errors to exceptions
                    \set_error_handler([$__psysh__, 'handleError']);

                    // Evaluate the current code buffer
                    $_ = eval($__psysh__->onExecute($__psysh__->flushCode() ?: ExecutionClosure::NOOP_INPUT));
                } catch (\Throwable $_e) {
                    // Clean up on our way out.
                    \restore_error_handler();

                    throw $_e;
                } catch (\Exception $_e) {
                    // Clean up on our way out.
                    \restore_error_handler();

                    throw $_e;
                }
            } catch (BreakException $_e) {
                $__psysh__->writeException($_e);

                return;
            } catch (ThrowUpException $_e) {
                $__psysh__->writeException($_e);

                throw $_e;
            } catch (\TypeError $_e) {
                $__psysh__->writeException(TypeErrorException::fromTypeError($_e));
            } catch (\Error $_e) {
                $__psysh__->writeException(ErrorException::fromError($_e));
            } catch (\Exception $_e) {
                $__psysh__->writeException($_e);
            }

            \restore_error_handler();

            $__psysh__->writeReturnValue($_);

            // Save execution scope variables for next time
            $__psysh__->setScopeVariables(\get_defined_vars());

            return $_;
        });
    }
}
