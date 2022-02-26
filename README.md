<!-- AUTO-GENERATED-CONTENT:START (STARTER) -->
<p align="center">
  <a href="https://www.gatsbyjs.com">
    <img alt="Gatsby" src="https://www.gatsbyjs.com/Gatsby-Monogram.svg" width="60" />
  </a>
</p>
<h1 align="center">
  Gatsby Default Starter driven by YAML files geneated by Wordpress CMS
</h1>

This wordpress theme is designed to be a source API for my [GatsbyJs YAML starter](https://github.com/pawelwos/gatsby-yml-starter/tree/wordpress) (see wordpress branch). I use Timber for custom routes and YAML files boilerplates **(see /twig/api folder)**. So we have special API urls [https://cms.pawelwos.com/api/pages](https://cms.pawelwos.com/api/pages) and [https://cms.pawelwos.com/api/sections](https://cms.pawelwos.com/api/sections) which are the source endpoints for gatsbyjs. Gatsby downloads these YAML files on **onPreBootstrap** building stage and then use them for GraphQL. This theme is taking care of changing inline links and inline images to Gatsby's **`<Link>`** and **`<GatsbyImage>`** components. It also have a **preview mode** which loads any CMS changes using AJAX function in wordpress so no need for any additional builds and you can check what you are creating immediately.

Demo site: [https://gatsby.pawelwos.com](https://gatsby.pawelwos.com)

Demo CMS: [https://cms.pawelwos.com/wp-admin](https://cms.pawelwos.com/wp-admin)

```
Username: tester
Password: testthis
```

