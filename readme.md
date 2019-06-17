FF\Templating | Fast Forward Components Collection
===============================================================================

by Marco Stoll

- <marco.stoll@rocketmail.com>
- <http://marcostoll.de>
- <https://github.com/marcostoll>
- <https://github.com/marcostoll/FF-Templating>
------------------------------------------------------------------------------------------------------------------------

# What is the Fast Forward Components Collection?
The Fast Forward Components Collection, in short **Fast Forward** or **FF**, is a loosely coupled collection of code 
repositories each addressing common problems while building web application. Multiple **FF** components may be used 
together if desired. And some more complex **FF** components depend on other rather basic **FF** components.

**FF** is not a framework in and of itself and therefore should not be called so. 
But you may orchestrate multiple **FF** components to build an web application skeleton that provides the most common 
tasks.

# Introduction

This components provides the `TemplateRendererInterface` defining the basic api for adding concrete template rendering
class.

# Rendering Events

The `TemplateRendererInterface` defines that each concrete renderer must fire the following events while performing its
`render()` method:

- Templating\OnPreRender    : directly before rendering the template
- Templating\OnPostRender   : directly after rendering the template

Adding observers for this events lets you manipulate the rendering input data as well as the rendering output document
on your behalf.

See <https://github.com/marcostoll/FF-Events> for more information about **FF**-style event handling.

# Twig Support
A generic `TwigRenderer` renderer is provided using a `Twig\FilesystemLoader` to locate templates.

Consult <https://twig.symfony.com/> to learn more about **Twig**.